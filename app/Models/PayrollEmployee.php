<?php

namespace App\Models;

use App\Enums\EmployeeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PayrollEmployee extends Model
{
    protected $fillable = [
        'payroll_period_id',
        'employee_name',
        'employee_no',
        'tax_status',
        'employment_status',
        'base_salary',
        'working_hours',
        'employee_type',
        'employee_category',
        'position',
        'allowances_config',
        'deductions_config',
        'bpjs_allowances_included',
        'notes',
        'total_working_days',
        'total_present_days',
        'total_overtime_hours',
        'total_overday_hours',
        'total_allowances',
        'bpjs_base_salary',
        'bpjs_jht_deduction',
        'bpjs_pensiun_deduction',
        'bpjs_kesehatan_deduction',
        'pph21_amount',
        'gross_salary',
        'total_deductions',
        'net_salary',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'working_hours' => 'decimal:2',
            'total_overtime_hours' => 'decimal:2',
            'total_overday_hours' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'bpjs_base_salary' => 'decimal:2',
            'bpjs_jht_deduction' => 'decimal:2',
            'bpjs_pensiun_deduction' => 'decimal:2',
            'bpjs_kesehatan_deduction' => 'decimal:2',
            'pph21_amount' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'allowances_config' => 'array',
            'deductions_config' => 'array',
            'bpjs_allowances_included' => 'array',
            'employee_category' => EmployeeCategory::class,
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(PayrollTimesheet::class);
    }

    public function slips(): HasMany
    {
        return $this->hasMany(PayrollSlip::class);
    }

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->base_salary / 173,
        );
    }

    public function calculateTotals(): void
    {
        $timesheets = $this->timesheets;
        $this->total_working_days = $timesheets->count();
        $this->total_present_days = $timesheets->where('attendance_status', 'present')->count();
        $this->total_overtime_hours = $timesheets->sum('overtime_hours');
        $this->total_allowances = $timesheets->sum('total_allowances');

        $this->calculateBpjsDeductions();
        $this->calculatePph21();

        $this->gross_salary = $this->base_salary + $this->total_allowances + $timesheets->sum('total_overtime_pay');
        $this->total_deductions = $this->bpjs_jht_deduction + $this->bpjs_pensiun_deduction + $this->bpjs_kesehatan_deduction + $this->pph21_amount;
        $this->net_salary = $this->gross_salary - $this->total_deductions;

        $this->save();
    }

    public function calculateBpjsDeductions(): void
    {
        if (!$this->period->enable_bpjs) return;

        $baseSalary = $this->base_salary;
        $jhtPensiunBase = min($baseSalary, $this->period->bpjs_max_salary_jht);
        $this->bpjs_jht_deduction = $jhtPensiunBase * ($this->period->bpjs_jht_rate / 100);
        $this->bpjs_pensiun_deduction = $jhtPensiunBase * ($this->period->bpjs_pensiun_rate / 100);

        $kesehatanBase = min($baseSalary, $this->period->bpjs_max_salary_kesehatan);
        $this->bpjs_kesehatan_deduction = $kesehatanBase * ($this->period->bpjs_kesehatan_rate / 100);
    }

    public function calculatePph21(): void
    {
        if (!$this->period->enable_pph21) return;
        $this->pph21_amount = $this->base_salary * 0.05; // Simplified
    }
}
