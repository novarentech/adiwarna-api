<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operational extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'operational';

    protected $fillable = [
        'date',
        'type',
        'description',
        'amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }
}
