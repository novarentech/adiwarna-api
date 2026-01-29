<?php

namespace App\Models;

use App\Enums\DeliveryNoteStatus;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryNote extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $fillable = [
        'dn_no',
        'date',
        'customer_id',
        'wo_no',
        'delivered_with',
        'vehicle_plate',
        'delivered_by',
        'received_by',
        'status',
        'notes',
        'isOther',
        'other',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => DeliveryNoteStatus::class,
            'isOther' => 'boolean',
            'other' => 'array'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    public function getDnDateAttribute(): string
    {
        if (!$this->date) {
            return '';
        }

        $month = (int) $this->date->format('n');
        $year = $this->date->format('Y');

        $romanNumerals = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return ($romanNumerals[$month] ?? $month) . '/' . $year;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    protected function searchableColumns(): array
    {
        // Complex search handled in scopeSearch
        return [];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('dn_no', 'like', "%{$search}%")
              ->orWhere('wo_no', 'like', "%{$search}%")
              ->orWhere('vehicle_plate', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($customerQuery) use ($search) {
                  $customerQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderByRaw("YEAR(date) {$direction}")
                     ->orderBy('dn_no', $direction);
    }

    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeWithItemsCount(Builder $query): Builder
    {
        return $query->withCount('items')->with(['customer']);
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithItems(array $data): self
    {
        return self::transactional(function () use ($data) {
            $dnData = array_diff_key($data, array_flip(['items', 'name', 'phone', 'address']));
            
            if (isset($data['isOther']) && $data['isOther']) {
                $dnData['other'] = [
                    'phone' => $data['phone'] ?? '',
                    'address' => $data['address'] ?? '',
                    'name' => $data['name'] ?? '',
                ];
            }

            $deliveryNote = self::create($dnData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $deliveryNote->items()->create($item);
                }
            }

            return $deliveryNote->load(['items', 'customer']);
        });
    }

    public function updateWithItems(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $dnData = array_diff_key($data, array_flip(['items', 'name', 'phone', 'address']));

            if (isset($data['isOther']) && $data['isOther']) {
                $dnData['other'] = [
                    'phone' => $data['phone'] ?? '',
                    'address' => $data['address'] ?? '',
                    'name' => $data['name'] ?? '',
                ];
            }

            $this->update($dnData);

            if (isset($data['items'])) {
                $existingIds = [];
                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        // Ensure item belongs to this DN
                        $item = $this->items()->find($itemData['id']);
                        if ($item) {
                            $item->update($itemData);
                            $existingIds[] = $item->id;
                        } else {
                            // If ID passed but not in this DN, treat as new? Original code treated as new if not in existing IDs.
                            // Original: if (in_array($itemData['id'], $existingItemIds)) -> update. else -> create.
                            $newItem = $this->items()->create($itemData);
                            $existingIds[] = $newItem->id;
                        }
                    } else {
                        $newItem = $this->items()->create($itemData);
                        $existingIds[] = $newItem->id;
                    }
                }
                // Delete removed
                $this->items()->whereNotIn('id', $existingIds)->delete();
            }

            return $this->load(['items', 'customer']);
        });
    }
}
