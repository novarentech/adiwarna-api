<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAssignmentEmployee extends Model
{
    protected $fillable = ['work_assignment_id', 'employee_id', 'detail'];

    public function workAssignment(): BelongsTo
    {
        return $this->belongsTo(WorkAssignment::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
