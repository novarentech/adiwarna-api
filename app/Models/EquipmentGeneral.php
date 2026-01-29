<?php

namespace App\Models;

use App\Enums\CalibrationAgency;
use App\Enums\CalibrationDuration;
use App\Enums\EquipmentCondition;
use App\Models\Concerns\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentGeneral extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'equipment_general';

    protected $fillable = [
        'description',
        'merk_type',
        'serial_number',
        'calibration_date',
        'duration_months',
        'expired_date',
        'calibration_agency',
        'condition',
    ];

    protected function casts(): array
    {
        return [
            'calibration_date' => 'date',
            'expired_date' => 'date',
            'duration_months' => CalibrationDuration::class,
            'calibration_agency' => CalibrationAgency::class,
            'condition' => EquipmentCondition::class,
        ];
    }

    /**
     * Boot method to auto-calculate expired_date
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($equipment) {
            if ($equipment->calibration_date && $equipment->duration_months) {
                $equipment->expired_date = Carbon::parse($equipment->calibration_date)
                    ->addMonths($equipment->duration_months->value);
            }
        });
    }

    /**
     * Define searchable columns for equipment general search
     */
    protected function searchableColumns(): array
    {
        return [
            'description',
            'merk_type',
            'serial_number',
            'calibration_agency',
            'condition'
        ];
    }

    /**
     * Scope: Filter by Condition
     */
    public function scopeByCondition(Builder $query, string $condition): Builder
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope: Filter by Calibration Agency
     */
    public function scopeByCalibrationAgency(Builder $query, string $agency): Builder
    {
        return $query->where('calibration_agency', $agency);
    }
}

