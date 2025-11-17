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

    public function getPaginatedPRs(int $perPage = 15): LengthAwarePaginator
    {
        return $this->prRepository->withItems()->paginate($perPage);
    }

    public function getPRById(int $id): ?PurchaseRequisition
    {
        return $this->prRepository->withItems()->find($id);
    }

    public function createPRWithItems(array $data): PurchaseRequisition
    {
        return $this->executeInTransaction(function () use ($data) {
            // Calculate total amount
            $totalAmount = 0;
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $totalAmount += ($item['qty'] * $item['unit_price']);
                }
            }

            // Apply discount
            $discount = $data['discount'] ?? 0;
            $totalAmount = $totalAmount - ($totalAmount * ($discount / 100));

            $prData = array_diff_key($data, array_flip(['items']));
            $prData['total_amount'] = $totalAmount;

            $pr = $this->prRepository->create($prData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $pr->items()->create($item);
                }
            }

            return $pr->load('items');
        });
    }

    public function updatePRWithItems(int $id, array $data): PurchaseRequisition
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            // Calculate total amount
            $totalAmount = 0;
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $totalAmount += ($item['qty'] * $item['unit_price']);
                }
            }

            // Apply discount
            $discount = $data['discount'] ?? 0;
            $totalAmount = $totalAmount - ($totalAmount * ($discount / 100));

            $prData = array_diff_key($data, array_flip(['items']));
            $prData['total_amount'] = $totalAmount;

            $pr = $this->prRepository->update($id, $prData);

            if (isset($data['items'])) {
                $pr->items()->delete();
                foreach ($data['items'] as $item) {
                    $pr->items()->create($item);
                }
            }

            return $pr->load('items');
        });
    }

    public function deletePR(int $id): bool
    {
        return $this->prRepository->delete($id);
    }
}
