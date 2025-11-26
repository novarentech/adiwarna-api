<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_no',
        'name',
        'position',
    ];

    /**
     * Get work assignments for this employee
     */
    public function workAssignments(): BelongsToMany
    {
        return $this->belongsToMany(WorkAssignment::class, 'work_assignment_employees')
            ->withPivot('detail')
            ->withTimestamps();
    }
}
