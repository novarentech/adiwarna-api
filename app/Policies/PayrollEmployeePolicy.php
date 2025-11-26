<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\PayrollEmployee;
use App\Models\User;

class PayrollEmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function view(User $user, PayrollEmployee $payrollEmployee): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function update(User $user, PayrollEmployee $payrollEmployee): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function delete(User $user, PayrollEmployee $payrollEmployee): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
