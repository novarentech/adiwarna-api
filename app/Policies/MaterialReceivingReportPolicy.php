<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\MaterialReceivingReport;
use App\Models\User;

class MaterialReceivingReportPolicy
{
    /**
     * Determine if the user can view any material receiving reports.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the material receiving report.
     */
    public function view(User $user, MaterialReceivingReport $materialReceivingReport): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create material receiving reports.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the material receiving report.
     */
    public function update(User $user, MaterialReceivingReport $materialReceivingReport): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the material receiving report.
     */
    public function delete(User $user, MaterialReceivingReport $materialReceivingReport): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
