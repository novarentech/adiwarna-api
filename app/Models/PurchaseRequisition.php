<?php

namespace App\Models;

use App\Enums\PurchaseRequisitionRouting;
use App\Enums\PurchaseRequisitionStatus;
use App\Enums\PurchaseRequisitionSupplier;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'purchase_requisitions';

    protected $fillable = [
        'pr_no',
        'date',
        'po_no_cash',
        'supplier',
        'routing',
        'sub_total',
        'vat_percentage',
        'vat_amount',
        'total_amount',
        'requested_by',
        'requested_position',
        'approved_by',
        'approved_position',
        'authorized_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sub_total' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'supplier' => PurchaseRequisitionSupplier::class,
            'routing' => PurchaseRequisitionRouting::class,
            'status' => PurchaseRequisitionStatus::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    public function getPrDateAttribute(): string
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
        return ['pr_no', 'supplier'];
    }

    public function scopeSearch($query, $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            foreach ($this->searchableColumns() as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
{
    $driver = DB::getDriverName();

    $yearExpression = match ($driver) {
        'sqlite' => "strftime('%Y', date)",
        'pgsql'  => "EXTRACT(YEAR FROM date)",
        default  => "YEAR(date)", // mysql / mariadb
    };

    return $query
        ->orderByRaw("$yearExpression $direction")
        ->orderBy('pr_no', $direction);
}

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithItems(array $data): self
    {
        return self::transactional(function () use ($data) {
            $itemsData = $data['items'] ?? [];
            $vatPercentage = $data['vat_percentage'] ?? 10;
            
            // Calculate totals
            $calculations = self::calculateTotals($itemsData, $vatPercentage);
            
            $prData = array_merge(
                array_diff_key($data, array_flip(['items'])),
                $calculations
            );

            $pr = self::create($prData);

            // Create items
            foreach ($itemsData as $item) {
                // Ensure total_price is set, though calculateTotals uses it for sum, 
                // we need to save it to DB item too? Usually item DB has total_price or triggers.
                // Service was setting it manually.
                $item['total_price'] = $item['qty'] * $item['unit_price'];
                $pr->items()->create($item);
            }

            return $pr->load('items');
        });
    }

    public function updateWithItems(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $itemsData = $data['items'] ?? [];
            $vatPercentage = $data['vat_percentage'] ?? $this->vat_percentage ?? 10;

            // Calculate new totals based on submitted items
            // Note: Update logic in service implies we only care about submitted items for totals?
            // "Calculate new totals based on submitted items" - wait, if partial update of items, does it mean we assume full replacement list in API?
            // The service logic: "Calculate totals" loop over $data['items'].
            // Then it deletes removed items. So yes, it assumes $data['items'] is the FULL list of current items.
            
            $calculations = self::calculateTotals($itemsData, $vatPercentage);

            $prData = array_merge(
                array_diff_key($data, array_flip(['items'])),
                $calculations
            );

            $this->update($prData);

            // Sync items
            $existingIds = [];
            foreach ($itemsData as $itemData) {
                $itemData['total_price'] = $itemData['qty'] * $itemData['unit_price'];
                
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

            return $this->load('items');
        });
    }

    protected static function calculateTotals(array $items, float $vatPercentage): array
    {
        $subTotal = 0;
        foreach ($items as $item) {
            $subTotal += ($item['qty'] * $item['unit_price']);
        }

        $vatAmount = $subTotal * ($vatPercentage / 100);
        $totalAmount = $subTotal + $vatAmount;

        return [
            'sub_total' => $subTotal,
            'vat_percentage' => $vatPercentage,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount,
        ];
    }
}
