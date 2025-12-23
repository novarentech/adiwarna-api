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
        'po_inv_pr_no',
        'supplier',
        'receiving_date',
        'order_by',
        'received_by',
        'acknowledge_by',
        'status',
        'notes',
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
