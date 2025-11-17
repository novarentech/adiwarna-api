<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_name',
        'customer_id',
        'date',
        'status',
        'description',
        'milestones',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'milestones' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
