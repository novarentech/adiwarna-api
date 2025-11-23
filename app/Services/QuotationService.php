<?php

namespace App\Services;

use App\Contracts\Repositories\QuotationRepositoryInterface;
use App\Models\Quotation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuotationService extends BaseService
{
    public function __construct(
        protected QuotationRepositoryInterface $quotationRepository
    ) {}

    public function getPaginatedQuotations(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->quotationRepository->withCustomerOnly();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getQuotationById(int $id): ?Quotation
    {
        return $this->quotationRepository->withRelations()->find($id);
    }

    public function createQuotation(array $data): Quotation
    {
        return $this->executeInTransaction(function () use ($data) {
            $quotationData = array_diff_key($data, array_flip(['items', 'adiwarnas', 'clients']));
            $quotation = $this->quotationRepository->create($quotationData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $quotation->items()->create($item);
                }
            }

            if (isset($data['adiwarnas'])) {
                foreach ($data['adiwarnas'] as $adiwarna) {
                    $quotation->adiwarnas()->create($adiwarna);
                }
            }

            if (isset($data['clients'])) {
                foreach ($data['clients'] as $client) {
                    $quotation->clients()->create($client);
                }
            }

            return $quotation->load(['items', 'adiwarnas', 'clients']);
        });
    }

    public function updateQuotation(int $id, array $data): Quotation
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            // Separate quotation data from nested relations
            $quotationData = array_diff_key($data, array_flip(['items', 'adiwarnas', 'clients']));

            // Update quotation only if there's data to update
            if (!empty($quotationData)) {
                $quotation = $this->quotationRepository->update($id, $quotationData);
            } else {
                $quotation = $this->quotationRepository->find($id);
            }

            // Update items with partial update support
            if (isset($data['items'])) {
                $existingItemIds = [];

                foreach ($data['items'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        // Update existing item
                        $item = $quotation->items()->find($itemData['id']);
                        if ($item) {
                            $item->update($itemData);
                            $existingItemIds[] = $itemData['id'];
                        }
                    } else {
                        // Create new item
                        $newItem = $quotation->items()->create($itemData);
                        $existingItemIds[] = $newItem->id;
                    }
                }

                // Delete items that are not in the request
                $quotation->items()->whereNotIn('id', $existingItemIds)->delete();
            }

            // Update adiwarnas with partial update support
            if (isset($data['adiwarnas'])) {
                $existingAdiwarnaIds = [];

                foreach ($data['adiwarnas'] as $adiwarnaData) {
                    if (isset($adiwarnaData['id']) && $adiwarnaData['id']) {
                        // Update existing adiwarna
                        $adiwarna = $quotation->adiwarnas()->find($adiwarnaData['id']);
                        if ($adiwarna) {
                            $adiwarna->update($adiwarnaData);
                            $existingAdiwarnaIds[] = $adiwarnaData['id'];
                        }
                    } else {
                        // Create new adiwarna
                        $newAdiwarna = $quotation->adiwarnas()->create($adiwarnaData);
                        $existingAdiwarnaIds[] = $newAdiwarna->id;
                    }
                }

                // Delete adiwarnas that are not in the request
                $quotation->adiwarnas()->whereNotIn('id', $existingAdiwarnaIds)->delete();
            }

            // Update clients with partial update support
            if (isset($data['clients'])) {
                $existingClientIds = [];

                foreach ($data['clients'] as $clientData) {
                    if (isset($clientData['id']) && $clientData['id']) {
                        // Update existing client
                        $client = $quotation->clients()->find($clientData['id']);
                        if ($client) {
                            $client->update($clientData);
                            $existingClientIds[] = $clientData['id'];
                        }
                    } else {
                        // Create new client
                        $newClient = $quotation->clients()->create($clientData);
                        $existingClientIds[] = $newClient->id;
                    }
                }

                // Delete clients that are not in the request
                $quotation->clients()->whereNotIn('id', $existingClientIds)->delete();
            }

            return $quotation->load(['items', 'adiwarnas', 'clients']);
        });
    }

    public function deleteQuotation(int $id): bool
    {
        return $this->quotationRepository->delete($id);
    }
}
