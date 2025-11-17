<?php

namespace App\Services;

use App\Contracts\Repositories\EquipmentGeneralRepositoryInterface;
use App\Contracts\Repositories\EquipmentProjectRepositoryInterface;
use App\Models\EquipmentGeneral;
use App\Models\EquipmentProject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EquipmentService extends BaseService
{
    public function __construct(
        protected EquipmentGeneralRepositoryInterface $equipmentGeneralRepository,
        protected EquipmentProjectRepositoryInterface $equipmentProjectRepository
    ) {}

    // Equipment General methods
    public function getAllGeneral(): Collection
    {
        return $this->equipmentGeneralRepository->all();
    }

    public function getPaginatedGeneral(int $perPage = 15): LengthAwarePaginator
    {
        return $this->equipmentGeneralRepository->paginate($perPage);
    }

    public function getGeneralById(int $id): ?EquipmentGeneral
    {
        return $this->equipmentGeneralRepository->find($id);
    }

    public function createGeneral(array $data): EquipmentGeneral
    {
        return $this->equipmentGeneralRepository->create($data);
    }

    public function updateGeneral(int $id, array $data): EquipmentGeneral
    {
        return $this->equipmentGeneralRepository->update($id, $data);
    }

    public function deleteGeneral(int $id): bool
    {
        return $this->equipmentGeneralRepository->delete($id);
    }

    // Equipment Project methods
    public function getAllProject(): Collection
    {
        return $this->equipmentProjectRepository->withCustomer()->all();
    }

    public function getPaginatedProject(int $perPage = 15): LengthAwarePaginator
    {
        return $this->equipmentProjectRepository->withCustomer()->paginate($perPage);
    }

    public function getProjectById(int $id): ?EquipmentProject
    {
        return $this->equipmentProjectRepository->withCustomer()->find($id);
    }

    public function createProject(array $data): EquipmentProject
    {
        return $this->equipmentProjectRepository->create($data);
    }

    public function updateProject(int $id, array $data): EquipmentProject
    {
        return $this->equipmentProjectRepository->update($id, $data);
    }

    public function deleteProject(int $id): bool
    {
        return $this->equipmentProjectRepository->delete($id);
    }
}
