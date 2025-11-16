<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignment_no',
        'assignment_year',
        'date',
        'ref_no',
        'ref_year',
        'customer_id',
        'customer_location_id',
        'ref_po_no_instruction',
        'location',
        'scope',
        'estimation',
        'mobilization',
        'auth_name',
        'auth_pos',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
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
        return $this->belongsToMany(Employee::class, 'work_assignment_employees')
            ->withPivot('detail')
            ->withTimestamps();
    }
}
