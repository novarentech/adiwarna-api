<?php

namespace App\Models;

use App\Enums\PurchaseRequisitionRouting;
use App\Enums\PurchaseRequisitionStatus;
use App\Enums\PurchaseRequisitionSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes;

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

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }

    /**
     * Get formatted pr_date with Roman numerals from date field
     */
    public function getPrDateAttribute(): string
    {
        if (!$this->date) {
            return '';
        }

        $month = (int) $this->date->format('n'); // 1-12
        $year = $this->date->format('Y');

        $romanNumerals = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $romanMonth = $romanNumerals[$month] ?? $month;

        return $romanMonth . '/' . $year;
    }
}
