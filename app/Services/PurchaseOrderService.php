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

    public function getPaginatedPurchaseOrders(int $perPage = 15, ?int $customerId = null): LengthAwarePaginator
    {
        $query = $this->purchaseOrderRepository->withRelations();

        if ($customerId) {
            $query->filterByCustomer($customerId);
        }

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
                $purchaseOrder->items()->delete();
                foreach ($data['items'] as $item) {
                    $purchaseOrder->items()->create($item);
                }
            }

            return $purchaseOrder->load('items');
        });
    }

    public function deletePurchaseOrder(int $id): bool
    {
        return $this->purchaseOrderRepository->delete($id);
    }
}
