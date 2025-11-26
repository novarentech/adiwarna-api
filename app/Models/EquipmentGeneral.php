<?php

namespace App\Models;

use App\Enums\CalibrationAgency;
use App\Enums\CalibrationDuration;
use App\Enums\EquipmentCondition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentGeneral extends Model
{
    use HasFactory, SoftDeletes;

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
}
