<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_projects';

    protected $fillable = [
        'customer_id',
        'customer_location_id',
        'project_date',
        'prepared_by',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
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

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipmentGeneral::class,
            'equipment_project_items',
            'equipment_project_id',
            'equipment_general_id'
        )->withTimestamps();
    }
}
