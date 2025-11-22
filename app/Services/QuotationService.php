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

            // Update items only if provided
            if (isset($data['items'])) {
                $quotation->items()->delete();
                foreach ($data['items'] as $item) {
                    $quotation->items()->create($item);
                }
            }

            // Update adiwarnas only if provided
            if (isset($data['adiwarnas'])) {
                $quotation->adiwarnas()->delete();
                foreach ($data['adiwarnas'] as $adiwarna) {
                    $quotation->adiwarnas()->create($adiwarna);
                }
            }

            // Update clients only if provided
            if (isset($data['clients'])) {
                $quotation->clients()->delete();
                foreach ($data['clients'] as $client) {
                    $quotation->clients()->create($client);
                }
            }

            return $quotation->load(['items', 'adiwarnas', 'clients']);
        });
    }

    public function deleteQuotation(int $id): bool
    {
        return $this->quotationRepository->delete($id);
    }
}
