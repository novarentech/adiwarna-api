<?php

namespace App\Services;

use App\Contracts\Repositories\OperationalRepositoryInterface;
use App\Models\Operational;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OperationalService extends BaseService
{
    public function __construct(
        protected OperationalRepositoryInterface $operationalRepository
    ) {}

    public function getAllOperational(): Collection
    {
        return $this->operationalRepository->all();
    }

    public function getPaginatedOperational(int $perPage = 15): LengthAwarePaginator
    {
        return $this->operationalRepository->paginate($perPage);
    }

    public function getOperationalById(int $id): ?Operational
    {
        return $this->operationalRepository->find($id);
    }

    public function createOperational(array $data): Operational
    {
        return $this->operationalRepository->create($data);
    }

    public function updateOperational(int $id, array $data): Operational
    {
        return $this->operationalRepository->update($id, $data);
    }

    public function deleteOperational(int $id): bool
    {
        return $this->operationalRepository->delete($id);
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->operationalRepository->byDateRange($startDate, $endDate);
    }

    public function getByType(string $type): Collection
    {
        return $this->operationalRepository->byType($type);
    }
}
