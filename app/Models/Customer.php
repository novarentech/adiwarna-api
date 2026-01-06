<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_no',
        'name',
        'phone_number',
        'address',
    ];

    /**
     * Get customer locations
     */
    public function locations(): HasMany
    {
        return $this->hasMany(CustomerLocation::class);
    }

    /**
     * Get customer quotations
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get customer purchase orders
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get customer work assignments
     */
    public function workAssignments(): HasMany
    {
        return $this->hasMany(WorkAssignment::class);
    }

    /**
     * Get customer daily activities
     */
    public function dailyActivities(): HasMany
    {
        return $this->hasMany(DailyActivity::class);
    }

    /**
     * Get customer delivery notes
     */
    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }
}
