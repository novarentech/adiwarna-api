<?php

namespace App\Services;

use App\Contracts\Repositories\DeliveryNoteRepositoryInterface;
use App\Contracts\Repositories\DeliveryNoteItemRepositoryInterface;
use App\Models\DeliveryNote;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeliveryNoteService extends BaseService
{
    public function __construct(
        protected DeliveryNoteRepositoryInterface $deliveryNoteRepository,
        protected DeliveryNoteItemRepositoryInterface $deliveryNoteItemRepository
    ) {}

    public function getPaginatedDeliveryNotes(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->deliveryNoteRepository
            ->withItemsCount()
            ->search($search)
            ->paginate($perPage);
    }

    public function getDeliveryNoteById(int $id): ?DeliveryNote
    {
        return $this->deliveryNoteRepository->withItems()->find($id);
    }

    public function createDeliveryNoteWithItems(array $data): DeliveryNote
    {
        return $this->executeInTransaction(function () use ($data) {
            $deliveryNoteData = array_diff_key($data, array_flip(['items']));
            $deliveryNote = $this->deliveryNoteRepository->create($deliveryNoteData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $deliveryNote->items()->create($item);
                }
            }

            return $deliveryNote->load('items');
        });
    }

    public function updateDeliveryNoteWithItems(int $id, array $data): DeliveryNote
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $deliveryNote = $this->deliveryNoteRepository->find($id);
            $deliveryNoteData = array_diff_key($data, array_flip(['items']));

            if (isset($data['items'])) {
                // Get existing item IDs BEFORE processing
                $existingItemIds = $deliveryNote->items()->pluck('id')->toArray();
                $submittedItemIds = [];

                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        // Check if the item ID actually belongs to this delivery note
                        if (in_array($itemData['id'], $existingItemIds)) {
                            // Update existing item that belongs to this delivery note
                            $submittedItemIds[] = $itemData['id'];
                            $itemDataWithoutId = $itemData;
                            unset($itemDataWithoutId['id']);
                            $this->deliveryNoteItemRepository->update($itemData['id'], $itemDataWithoutId);
                        } else {
                            // Item ID doesn't belong to this delivery note, treat as new item
                            $itemDataWithoutId = $itemData;
                            unset($itemDataWithoutId['id']);
                            $deliveryNote->items()->create($itemDataWithoutId);
                        }
                    } else {
                        // Create new item
                        $itemDataWithoutId = $itemData;
                        unset($itemDataWithoutId['id']);
                        $deliveryNote->items()->create($itemDataWithoutId);
                    }
                }

                // Delete items that were not submitted (removed from the request)
                $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
                if (!empty($itemsToDelete)) {
                    $deliveryNote->items()->whereIn('id', $itemsToDelete)->delete();
                }
            }

            $deliveryNote = $this->deliveryNoteRepository->update($id, $deliveryNoteData);
            return $deliveryNote->load('items');
        });
    }

    public function deleteDeliveryNote(int $id): bool
    {
        return $this->deliveryNoteRepository->delete($id);
    }
}
