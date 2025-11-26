<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\PayrollPeriod;
use App\Models\User;

class PayrollPeriodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function view(User $user, PayrollPeriod $payrollPeriod): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function update(User $user, PayrollPeriod $payrollPeriod): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function delete(User $user, PayrollPeriod $payrollPeriod): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
