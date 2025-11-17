<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_projects';

    protected $fillable = [
        'project_name',
        'customer_id',
        'equipment_name',
        'equipment_type',
        'quantity',
        'condition',
        'assigned_date',
        'return_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'return_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
