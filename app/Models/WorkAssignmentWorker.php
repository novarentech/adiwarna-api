<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAssignmentWorker extends Model
{
    use HasFactory;

    protected $table = 'work_assignment_workers';

    protected $fillable = [
        'work_assignment_id',
        'worker_name',
        'position',
    ];

    public function workAssignment(): BelongsTo
    {
        return $this->belongsTo(WorkAssignment::class);
    }
}
