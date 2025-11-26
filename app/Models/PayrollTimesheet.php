<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollTimesheet extends Model
{
    protected $fillable = [
        'payroll_employee_id',
        'date',
        'attendance_status',
        'regular_hours',
        'overtime_hours',
        'total_allowances',
        'total_overtime_pay',
        'late_minutes',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'total_overtime_pay' => 'decimal:2',
            'attendance_status' => AttendanceStatus::class,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PayrollEmployee::class, 'payroll_employee_id');
    }
}
