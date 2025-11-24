<?php

namespace App\Services;

use App\Contracts\Repositories\WorkOrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TrackRecordService extends BaseService
{
    public function __construct(
        protected WorkOrderRepositoryInterface $workOrderRepository
    ) {}

    /**
     * Get track records from work orders with filtering and search.
     */
    public function getTrackRecords(
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $this->workOrderRepository->withRelations();

        // Filter by date range
        if ($startDate && $endDate) {
            $query->byDateRange($startDate, $endDate);
        }

        // Search by worker name, scope of work, customer, or work location
        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }
}
