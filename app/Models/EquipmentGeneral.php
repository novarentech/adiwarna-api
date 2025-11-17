<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentGeneral extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_general';

    protected $fillable = [
        'equipment_name',
        'equipment_type',
        'quantity',
        'condition',
        'specifications',
        'purchase_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'specifications' => 'array',
            'quantity' => 'integer',
        ];
    }
}
