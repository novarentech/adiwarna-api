<?php

namespace App\Models;

use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'quotations';

    protected $fillable = [
        'date',
        'ref_no',
        'ref_year',
        'customer_id',
        'pic_name',
        'pic_phone',
        'subject',
        'top',
        'valid_until',
        'clause',
        'workday',
        'auth_name',
        'auth_position',
        'discount',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function adiwarnas(): HasMany
    {
        return $this->hasMany(QuotationAdiwarna::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(QuotationClient::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Searchable
    |--------------------------------------------------------------------------
    */
    protected function searchableColumns(): array
    {
        return [
            'pic_name',
            'subject',
        ];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('pic_name', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($subQ) use ($search) {
                  $subQ->where('name', 'like', "%{$search}%");
              });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('ref_year', $direction)
                     ->orderBy('ref_no', $direction);
    }

    public function scopeFilterByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['customer', 'items', 'adiwarnas', 'clients']);
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithItems(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $quotationData = array_diff_key($data, array_flip(['items', 'adiwarnas', 'clients']));
            
            // Auto-generate ref number logic could be here if needed, keeping it simple for now or assuming provided
            // For future: Quotation::max('ref_no') + 1 logic
            
            $quotation = self::create($quotationData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $quotation->items()->create($item);
                }
            }

            if (isset($data['adiwarnas'])) {
                foreach ($data['adiwarnas'] as $adiwarna) {
                    $quotation->adiwarnas()->create($adiwarna);
                }
            }

            if (isset($data['clients'])) {
                foreach ($data['clients'] as $client) {
                    $quotation->clients()->create($client);
                }
            }

            return $quotation->load(['items', 'adiwarnas', 'clients']);
        });
    }

    public function updateWithItems(array $data): self
    {
        return $this->executeInTransaction(function () use ($data) {
            $quotationData = array_diff_key($data, array_flip(['items', 'adiwarnas', 'clients']));

            $this->update($quotationData);

            // Sync interactions
            $this->syncRelation('items', $data['items'] ?? []);
            $this->syncRelation('adiwarnas', $data['adiwarnas'] ?? []);
            $this->syncRelation('clients', $data['clients'] ?? []);

            return $this->load(['items', 'adiwarnas', 'clients']);
        });
    }

    /**
     * Helper to sync One-To-Many relations (update existing, create new, delete missing)
     */
    protected function syncRelation(string $relationName, array $items): void
    {
        $existingIds = [];

        foreach ($items as $itemData) {
            if (isset($itemData['id']) && $itemData['id']) {
                $this->{$relationName}()->where('id', $itemData['id'])->update($itemData);
                $existingIds[] = $itemData['id'];
            } else {
                $newItem = $this->{$relationName}()->create($itemData);
                $existingIds[] = $newItem->id;
            }
        }

        // Delete removed items
        $this->{$relationName}()->whereNotIn('id', $existingIds)->delete();
    }
}
