<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\PayrollProject;
use App\Models\User;

class PayrollProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function view(User $user, PayrollProject $payrollProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function update(User $user, PayrollProject $payrollProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    public function delete(User $user, PayrollProject $payrollProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
