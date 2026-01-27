<?php

namespace App\Services;

use App\Contracts\Repositories\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PurchaseOrderService extends BaseService
{
    public function __construct(
        protected PurchaseOrderRepositoryInterface $purchaseOrderRepository
    ) {}

    public function getPaginatedPurchaseOrders(int $perPage = 15, ?string $search = null, string $sortOrder = 'desc'): LengthAwarePaginator
    {
        $query = $this->purchaseOrderRepository->withCustomerOnly();

        if ($search) {
            $query->search($search);
        }

        $query->sortBy($sortOrder);

        return $query->paginate($perPage);
    }

    public function getPurchaseOrderById(int $id): ?PurchaseOrder
    {
        return $this->purchaseOrderRepository->withRelations()->find($id);
    }

    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        return $this->executeInTransaction(function () use ($data) {
            $poData = array_diff_key($data, array_flip(['items']));
            $purchaseOrder = $this->purchaseOrderRepository->create($poData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $purchaseOrder->items()->create($item);
                }
            }

            return $purchaseOrder->load('items');
        });
    }

    public function updatePurchaseOrder(int $id, array $data): PurchaseOrder
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $poData = array_diff_key($data, array_flip(['items']));
            $purchaseOrder = $this->purchaseOrderRepository->update($id, $poData);

            if (isset($data['items'])) {
                $existingItemIds = [];

                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        // Update existing item
                        $item = $purchaseOrder->items()->find($itemData['id']);
                        if ($item) {
                            $item->update($itemData);
                            $existingItemIds[] = $itemData['id'];
                        }
                    } else {
                        // Create new item
                        $newItem = $purchaseOrder->items()->create($itemData);
                        $existingItemIds[] = $newItem->id;
                    }
                }

                // Delete items that are not in the request
                $purchaseOrder->items()->whereNotIn('id', $existingItemIds)->delete();
            }

            return $purchaseOrder->load('items');
        });
    }

    public function deletePurchaseOrder(int $id): bool
    {
        return $this->purchaseOrderRepository->delete($id);
    }
}
