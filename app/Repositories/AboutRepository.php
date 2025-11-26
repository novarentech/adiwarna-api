<?php

namespace App\Repositories;

use App\Contracts\Repositories\AboutRepositoryInterface;
use App\Models\About;

class AboutRepository extends BaseRepository implements AboutRepositoryInterface
{
    protected function model(): string
    {
        return About::class;
    }

    public function getActive(): ?About
    {
        return $this->model->where('is_active', true)->first();
    }

    public function setActive(int $id): bool
    {
        // Deactivate all
        $this->model->query()->update(['is_active' => false]);

        // Activate the specified one
        return $this->model->where('id', $id)->update(['is_active' => true]);
    }
}
