<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'work_order_no',
        'work_order_year',
        'date',
        'customer_id',
        'customer_location_id',
        'scope_of_work',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'work_order_year' => 'integer',
            'scope_of_work' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerLocation(): BelongsTo
    {
        return $this->belongsTo(CustomerLocation::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'work_order_employees')
            ->withPivot('id')
            ->withTimestamps();
    }
}
