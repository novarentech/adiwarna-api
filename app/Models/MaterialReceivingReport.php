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
        'po_year',
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
}
