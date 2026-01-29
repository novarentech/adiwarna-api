<?php

namespace App\Models;

use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentProject extends Model
{
    use HasFactory, SoftDeletes, Searchable, Transactional;

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

    /**
     * Define searchable columns for EquipmentProject (direct columns)
     */
    protected function searchableColumns(): array
    {
        return ['prepared_by', 'verified_by'];
    }

    /**
     * Enhanced search scope with relationship support
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('prepared_by', 'like', "%{$keyword}%")
                ->orWhere('verified_by', 'like', "%{$keyword}%")
                ->orWhereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                });
        });
    }

    /**
     * Scope: By Customer
     */
    public function scopeByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Create Project with Equipments Transactionally
     */
    public static function createWithEquipments(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $equipmentIds = $data['equipment_ids'] ?? [];
            unset($data['equipment_ids']);

            $project = self::create($data);

            if (!empty($equipmentIds)) {
                $project->equipments()->attach($equipmentIds);
            }

            return $project->load(['customer', 'customerLocation', 'equipments']);
        });
    }

    /**
     * Update Project with Equipments Transactionally
     */
    public function updateWithEquipments(array $data): self
    {
        return self::executeInTransaction(function () use ($data) {
            $equipmentIds = $data['equipment_ids'] ?? null;
            unset($data['equipment_ids']);

            $this->update($data);

            if ($equipmentIds !== null) {
                $this->equipments()->sync($equipmentIds);
            }

            return $this->load(['customer', 'customerLocation', 'equipments']);
        });
    }
}

