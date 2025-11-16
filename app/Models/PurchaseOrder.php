<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
