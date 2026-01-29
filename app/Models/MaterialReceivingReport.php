<?php

namespace App\Models;

use App\Enums\MaterialReceivingReportOrderBy;
use App\Enums\MaterialReceivingReportStatus;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialReceivingReport extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

    protected $table = 'material_receiving_reports';

    protected $fillable = [
        'po_no',
        'supplier',
        'receiving_date',
        'order_by',
        'received_by',
        'received_position',
        'acknowledge_by',
        'acknowledge_position',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'receiving_date' => 'date',
            'order_by' => MaterialReceivingReportOrderBy::class,
            'status' => MaterialReceivingReportStatus::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function items(): HasMany
    {
        return $this->hasMany(MaterialReceivingReportItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    public function getPoDateAttribute(): string
    {
        if (!$this->receiving_date) {
            return '';
        }

        $month = (int) $this->receiving_date->format('n');
        $year = $this->receiving_date->format('Y');

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
        return ['po_no', 'supplier', 'received_by'];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('po_no', 'like', "%{$search}%")
              ->orWhere('supplier', 'like', "%{$search}%")
              ->orWhere('received_by', 'like', "%{$search}%");
        });
    }

    public function scopeSortDefault(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderByRaw("YEAR(receiving_date) {$direction}")
                     ->orderBy('po_no', $direction);
    }

    public function scopeFilterByDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('receiving_date', [$startDate, $endDate]);
    }

    public function scopeWithItemsCount(Builder $query): Builder
    {
        return $query->withCount('items');
    }

    /*
    |--------------------------------------------------------------------------
    | Transactional Methods
    |--------------------------------------------------------------------------
    */
    public static function createWithItems(array $data): self
    {
        return self::transactional(function () use ($data) {
            $mrrData = array_diff_key($data, array_flip(['items']));
            
            $mrr = self::create($mrrData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $mrr->items()->create($item);
                }
            }

            return $mrr->load('items');
        });
    }

    public function updateWithItems(array $data): self
    {
        return $this->transactional(function () use ($data) {
            $mrrData = array_diff_key($data, array_flip(['items']));

            $this->update($mrrData);

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
