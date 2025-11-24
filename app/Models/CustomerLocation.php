<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'location_name',
    ];

    /**
     * Get the customer that owns the location
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
