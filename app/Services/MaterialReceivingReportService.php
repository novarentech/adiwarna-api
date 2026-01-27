<?php

namespace App\Services;

use App\Contracts\Repositories\MaterialReceivingReportRepositoryInterface;
use App\Contracts\Repositories\MaterialReceivingReportItemRepositoryInterface;
use App\Models\MaterialReceivingReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MaterialReceivingReportService extends BaseService
{
    public function __construct(
        protected MaterialReceivingReportRepositoryInterface $mrrRepository,
        protected MaterialReceivingReportItemRepositoryInterface $mrrItemRepository
    ) {}

    public function getPaginatedMRRs(int $perPage = 15, ?string $search = null, string $sortOrder = 'desc'): LengthAwarePaginator
    {
        return $this->mrrRepository
            ->withItemsCount()
            ->search($search)
            ->sortBy($sortOrder)
            ->paginate($perPage);
    }

    public function getMRRById(int $id): ?MaterialReceivingReport
    {
        return $this->mrrRepository->withItems()->find($id);
    }

    public function createMRRWithItems(array $data): MaterialReceivingReport
    {
        return $this->executeInTransaction(function () use ($data) {
            $mrrData = array_diff_key($data, array_flip(['items']));
            $mrr = $this->mrrRepository->create($mrrData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $mrr->items()->create($item);
                }
            }

            return $mrr->load('items');
        });
    }

    public function updateMRRWithItems(int $id, array $data): MaterialReceivingReport
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $mrrData = array_diff_key($data, array_flip(['items']));
            $mrr = $this->mrrRepository->update($id, $mrrData);

            if (isset($data['items'])) {
                // Get existing item IDs BEFORE processing
                $existingItemIds = $mrr->items()->pluck('id')->toArray();
                $submittedItemIds = [];

                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        // Update existing item
                        $submittedItemIds[] = $itemData['id'];
                        $itemDataWithoutId = $itemData;
                        unset($itemDataWithoutId['id']);
                        $this->mrrItemRepository->update($itemData['id'], $itemDataWithoutId);
                    } else {
                        // Create new item
                        $itemDataWithoutId = $itemData;
                        unset($itemDataWithoutId['id']);
                        $mrr->items()->create($itemDataWithoutId);
                    }
                }

                // Delete items that were not submitted (removed from the request)
                $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
                if (!empty($itemsToDelete)) {
                    $mrr->items()->whereIn('id', $itemsToDelete)->delete();
                }
            }

            return $mrr->load('items');
        });
    }

    public function deleteMRR(int $id): bool
    {
        return $this->mrrRepository->delete($id);
    }
}
