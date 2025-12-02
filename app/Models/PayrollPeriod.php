<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollPeriod extends Model
{
    protected $fillable = [
        'payroll_project_id',
        'month',
        'year',
        'period_start',
        'period_end',
        'status',
        'enable_progressive_ot',
        'enable_bpjs',
        'enable_pph21',
        'enable_meal_allowance',
        'enable_perjadin',
        'enable_driver_rules',
        'bpjs_jht_rate',
        'bpjs_pensiun_rate',
        'bpjs_kesehatan_rate',
        'bpjs_max_salary_jht',
        'bpjs_max_salary_kesehatan',
        'meal_allowance_rate',
        'perjadin_breakfast_rate',
        'perjadin_lunch_rate',
        'perjadin_dinner_rate',
        'perjadin_daily_rate',
        'perjadin_accommodation_rate',
        'driver_max_payroll_ot',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'enable_progressive_ot' => 'boolean',
            'enable_bpjs' => 'boolean',
            'enable_pph21' => 'boolean',
            'enable_meal_allowance' => 'boolean',
            'enable_perjadin' => 'boolean',
            'enable_driver_rules' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(PayrollProject::class, 'payroll_project_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(PayrollEmployee::class);
    }

    public function slips(): HasMany
    {
        return $this->hasMany(PayrollSlip::class);
    }
}
