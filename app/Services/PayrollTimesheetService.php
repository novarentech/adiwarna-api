<?php

namespace App\Services;

use App\Models\PayrollTimesheet;
use App\Models\PayrollEmployee;

class PayrollTimesheetService extends BaseService
{
    public function createTimesheet(int $employeeId, array $data): PayrollTimesheet
    {
        $data['payroll_employee_id'] = $employeeId;
        return PayrollTimesheet::create($data);
    }

    public function updateTimesheet(int $employeeId, string $date, array $data): PayrollTimesheet
    {
        $timesheet = PayrollTimesheet::where('payroll_employee_id', $employeeId)
            ->where('date', $date)
            ->firstOrFail();

        $timesheet->update($data);
        return $timesheet;
    }

    public function deleteTimesheet(int $employeeId, string $date): bool
    {
        return PayrollTimesheet::where('payroll_employee_id', $employeeId)
            ->where('date', $date)
            ->delete();
    }

    public function bulkUpdateTimesheets(int $employeeId, array $timesheets): array
    {
        return $this->executeInTransaction(function () use ($employeeId, $timesheets) {
            $results = [];

            foreach ($timesheets as $timesheetData) {
                $timesheetData['payroll_employee_id'] = $employeeId;

                $timesheet = PayrollTimesheet::updateOrCreate(
                    [
                        'payroll_employee_id' => $employeeId,
                        'date' => $timesheetData['date'],
                    ],
                    $timesheetData
                );

                $results[] = $timesheet;
            }

            // Trigger recalculation
            $employee = PayrollEmployee::find($employeeId);
            if ($employee) {
                $employee->calculateTotals();
            }

            return $results;
        });
    }
}
