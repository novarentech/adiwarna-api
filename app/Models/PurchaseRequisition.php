<?php

namespace App\Models;

use App\Enums\PurchaseRequisitionRouting;
use App\Enums\PurchaseRequisitionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_no',
        'rev_no',
        'date',
        'required_delivery',
        'po_no_cash',
        'supplier',
        'place_of_delivery',
        'routing',
        'sub_total',
        'vat_percentage',
        'vat_amount',
        'total_amount',
        'requested_by',
        'approved_by',
        'authorized_by',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'required_delivery' => 'date',
            'sub_total' => 'decimal:2',
            'vat_percentage' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'routing' => PurchaseRequisitionRouting::class,
            'status' => PurchaseRequisitionStatus::class,
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }
}
