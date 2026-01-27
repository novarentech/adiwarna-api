<?php

namespace App\Services;

use App\Contracts\Repositories\PurchaseRequisitionRepositoryInterface;
use App\Contracts\Repositories\PurchaseRequisitionItemRepositoryInterface;
use App\Models\PurchaseRequisition;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PurchaseRequisitionService extends BaseService
{
    public function __construct(
        protected PurchaseRequisitionRepositoryInterface $prRepository,
        protected PurchaseRequisitionItemRepositoryInterface $prItemRepository
    ) {}

    public function getPaginatedPRs(int $perPage = 15, ?string $search = null, string $sortOrder = 'desc'): LengthAwarePaginator
    {
        return $this->prRepository
            ->search($search)
            ->sortBy($sortOrder)
            ->paginate($perPage);
    }

    public function getPRById(int $id): ?PurchaseRequisition
    {
        return $this->prRepository->withItems()->find($id);
    }

    public function createPRWithItems(array $data): PurchaseRequisition
    {
        return $this->executeInTransaction(function () use ($data) {
            $prData = array_diff_key($data, array_flip(['items']));

            // Calculate totals and prepare items
            $subTotal = 0;
            $itemsToCreate = [];

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $item['total_price'] = $item['qty'] * $item['unit_price'];
                    $subTotal += $item['total_price'];
                    $itemsToCreate[] = $item;
                }
            }

            $vatPercentage = $data['vat_percentage'] ?? 10;
            $vatAmount = $subTotal * ($vatPercentage / 100);
            $totalAmount = $subTotal + $vatAmount;

            $prData['sub_total'] = $subTotal;
            $prData['vat_percentage'] = $vatPercentage;
            $prData['vat_amount'] = $vatAmount;
            $prData['total_amount'] = $totalAmount;

            $pr = $this->prRepository->create($prData);

            // Create items with calculated total_price
            foreach ($itemsToCreate as $item) {
                $pr->items()->create($item);
            }

            return $pr->load('items');
        });
    }

    public function updatePRWithItems(int $id, array $data): PurchaseRequisition
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $pr = $this->prRepository->find($id);
            $prData = array_diff_key($data, array_flip(['items']));

            if (isset($data['items'])) {
                // Get existing item IDs BEFORE processing
                $existingItemIds = $pr->items()->pluck('id')->toArray();
                $submittedItemIds = [];

                $subTotal = 0;
                foreach ($data['items'] as $itemData) {
                    // Calculate total price for each item
                    $itemData['total_price'] = $itemData['qty'] * $itemData['unit_price'];
                    $subTotal += $itemData['total_price'];

                    if (isset($itemData['id']) && $itemData['id']) {
                        // Check if the item ID actually belongs to this PR
                        if (in_array($itemData['id'], $existingItemIds)) {
                            // Update existing item that belongs to this PR
                            $submittedItemIds[] = $itemData['id'];
                            $itemDataWithoutId = $itemData;
                            unset($itemDataWithoutId['id']);
                            $this->prItemRepository->update($itemData['id'], $itemDataWithoutId);
                        } else {
                            // Item ID doesn't belong to this PR, treat as new item
                            $itemDataWithoutId = $itemData;
                            unset($itemDataWithoutId['id']);
                            $pr->items()->create($itemDataWithoutId);
                        }
                    } else {
                        // Create new item
                        $itemDataWithoutId = $itemData;
                        unset($itemDataWithoutId['id']);
                        $pr->items()->create($itemDataWithoutId);
                    }
                }

                // Delete items that were not submitted (removed from the request)
                $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
                if (!empty($itemsToDelete)) {
                    $pr->items()->whereIn('id', $itemsToDelete)->delete();
                }

                // Calculate totals
                $vatPercentage = $data['vat_percentage'] ?? $pr->vat_percentage ?? 10;
                $vatAmount = $subTotal * ($vatPercentage / 100);
                $totalAmount = $subTotal + $vatAmount;

                $prData['sub_total'] = $subTotal;
                $prData['vat_percentage'] = $vatPercentage;
                $prData['vat_amount'] = $vatAmount;
                $prData['total_amount'] = $totalAmount;
            }

            $pr = $this->prRepository->update($id, $prData);
            return $pr->load('items');
        });
    }

    public function deletePR(int $id): bool
    {
        return $this->prRepository->delete($id);
    }
}
