<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleWorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'wo_number',
        'wo_year',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'wo_year' => 'integer',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
