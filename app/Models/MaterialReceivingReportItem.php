<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\MaterialReceivingReportRemarks;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialReceivingReportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_receiving_report_id',
        'description',
        'order_qty',
        'received_qty',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'order_qty' => 'decimal:2',
            'received_qty' => 'decimal:2',
            'remarks' => MaterialReceivingReportRemarks::class,
        ];
    }

    public function materialReceivingReport(): BelongsTo
    {
        return $this->belongsTo(MaterialReceivingReport::class);
    }
}
