<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\PayrollTimesheet;
use App\Models\User;

class PayrollTimesheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function view(User $user, PayrollTimesheet $payrollTimesheet): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function update(User $user, PayrollTimesheet $payrollTimesheet): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function delete(User $user, PayrollTimesheet $payrollTimesheet): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
