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

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_no',
        'po_year',
        'date',
        'customer_id',
        'pic_name',
        'pic_phone',
        'required_date',
        'top_dp',
        'top_cod',
        'quotation_ref',
        'purchase_requisition_no',
        'purchase_requisition_year',
        'discount',
        'req_name',
        'req_pos',
        'app_name',
        'app_pos',
        'auth_name',
        'auth_pos',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'required_date' => 'date',
            'discount' => 'decimal:2',
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
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    protected function searchableColumns(): array
    {
        return ['pic_name', 'pic_phone'];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('pic_name', 'like', "%{$search}%")
              ->orWhere('pic_phone', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($subQ) use ($search) {
                  $subQ->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('po_year', $direction)
                     ->orderBy('po_no', $direction);
    }

    public function scopeFilterByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithItems(array $data): self
    {
        return self::transactional(function () use ($data) {
            $poData = array_diff_key($data, array_flip(['items']));
            
            $po = self::create($poData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $po->items()->create($item);
                }
            }

            return $po->load('items');
        });
    }

    public function updateWithItems(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $poData = array_diff_key($data, array_flip(['items']));

            $this->update($poData);

            if (isset($data['items'])) {
                $existingIds = [];
                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        $this->items()->where('id', $itemData['id'])->update($itemData);
                        $existingIds[] = $itemData['id'];
                    } else {
                        $newItem = $this->items()->create($itemData);
                        $existingIds[] = $newItem->id;
                    }
                }
                
                // Delete removed
                $this->items()->whereNotIn('id', $existingIds)->delete();
            }

            return $this->load('items');
        });
    }
}
