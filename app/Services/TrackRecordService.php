<?php

namespace App\Services;

use App\Contracts\Repositories\TrackRecordRepositoryInterface;
use App\Models\TrackRecord;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TrackRecordService extends BaseService
{
    public function __construct(
        protected TrackRecordRepositoryInterface $trackRecordRepository
    ) {}

    public function getAllTrackRecords(): Collection
    {
        return $this->trackRecordRepository->withCustomer()->all();
    }

    public function getPaginatedTrackRecords(int $perPage = 15): LengthAwarePaginator
    {
        return $this->trackRecordRepository->withCustomer()->paginate($perPage);
    }

    public function getTrackRecordById(int $id): ?TrackRecord
    {
        return $this->trackRecordRepository->withCustomer()->find($id);
    }

    public function createTrackRecord(array $data): TrackRecord
    {
        return $this->trackRecordRepository->create($data);
    }

    public function updateTrackRecord(int $id, array $data): TrackRecord
    {
        return $this->trackRecordRepository->update($id, $data);
    }

    public function deleteTrackRecord(int $id): bool
    {
        return $this->trackRecordRepository->delete($id);
    }

    public function getByCustomer(int $customerId): Collection
    {
        return $this->trackRecordRepository->byCustomer($customerId);
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->trackRecordRepository->byDateRange($startDate, $endDate);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->trackRecordRepository->byStatus($status);
    }
}
