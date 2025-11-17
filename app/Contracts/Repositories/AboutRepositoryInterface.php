<?php

namespace App\Contracts\Repositories;

use App\Models\About;

interface AboutRepositoryInterface extends RepositoryInterface
{
    /**
     * Get active company information
     */
    public function getActive(): ?About;

    /**
     * Set company information as active
     */
    public function setActive(int $id): bool;
}
