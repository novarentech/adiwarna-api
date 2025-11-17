<?php

namespace App\Repositories;

use App\Contracts\Repositories\EquipmentProjectRepositoryInterface;
use App\Models\EquipmentProject;
use Illuminate\Database\Eloquent\Collection;

class EquipmentProjectRepository extends BaseRepository implements EquipmentProjectRepositoryInterface
{
    protected function model(): string
    {
        return EquipmentProject::class;
    }

    public function withCustomer(): self
    {
        $this->query->with('customer');
        return $this;
    }

    public function byCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function byProjectName(string $projectName): Collection
    {
        return $this->model->where('project_name', 'like', "%{$projectName}%")->get();
    }
}
