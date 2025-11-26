<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\EquipmentGeneral;
use App\Models\EquipmentProject;
use App\Models\User;

class EquipmentPolicy
{
    /**
     * Determine if the user can view any equipment.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the equipment general.
     */
    public function viewGeneral(User $user, EquipmentGeneral $equipmentGeneral): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the equipment project.
     */
    public function viewProject(User $user, EquipmentProject $equipmentProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create equipment.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the equipment general.
     */
    public function updateGeneral(User $user, EquipmentGeneral $equipmentGeneral): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the equipment project.
     */
    public function updateProject(User $user, EquipmentProject $equipmentProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the equipment general.
     */
    public function deleteGeneral(User $user, EquipmentGeneral $equipmentGeneral): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the equipment project.
     */
    public function deleteProject(User $user, EquipmentProject $equipmentProject): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
