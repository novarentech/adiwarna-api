<?php

namespace App\Models;

use App\Enums\MaterialReceivingReportOrderBy;
use App\Enums\MaterialReceivingReportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialReceivingReport extends Model
{
    use HasFactory, SoftDeletes;

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

    public function items(): HasMany
    {
        return $this->hasMany(MaterialReceivingReportItem::class);
    }

    /**
     * Get formatted po_date with Roman numerals from receiving_date field
     */
    public function getPoDateAttribute(): string
    {
        if (!$this->receiving_date) {
            return '';
        }

        $month = (int) $this->receiving_date->format('n'); // 1-12
        $year = $this->receiving_date->format('Y');

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
