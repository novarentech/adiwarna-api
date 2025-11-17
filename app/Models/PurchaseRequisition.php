<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_no',
        'pr_year',
        'date',
        'supplier',
        'delivery_place',
        'discount',
        'notes',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }
}
