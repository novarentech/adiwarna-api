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

    public function getPaginatedGeneral(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->equipmentGeneralRepository;

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
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
        return $this->equipmentProjectRepository->withCustomerAndLocation()->all();
    }

    public function getPaginatedProject(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->equipmentProjectRepository->withCustomerAndLocation();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getProjectById(int $id): ?EquipmentProject
    {
        return $this->equipmentProjectRepository->withRelations()->find($id);
    }

    public function createProject(array $data): EquipmentProject
    {
        return $this->executeInTransaction(function () use ($data) {
            $equipmentIds = $data['equipment_ids'] ?? [];
            unset($data['equipment_ids']);

            $project = $this->equipmentProjectRepository->create($data);

            if (!empty($equipmentIds)) {
                $project->equipments()->attach($equipmentIds);
            }

            return $project->load(['customer', 'customerLocation', 'equipments']);
        });
    }

    public function updateProject(int $id, array $data): EquipmentProject
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $equipmentIds = $data['equipment_ids'] ?? null;
            unset($data['equipment_ids']);

            $project = $this->equipmentProjectRepository->update($id, $data);

            if ($equipmentIds !== null) {
                $project->equipments()->sync($equipmentIds);
            }

            return $project->load(['customer', 'customerLocation', 'equipments']);
        });
    }

    public function deleteProject(int $id): bool
    {
        return $this->equipmentProjectRepository->delete($id);
    }
}
