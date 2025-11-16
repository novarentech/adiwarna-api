<?php

namespace App\Services;

use App\Contracts\Repositories\QuotationRepositoryInterface;
use App\Models\Quotation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class QuotationService extends BaseService
{
    public function __construct(
        protected QuotationRepositoryInterface $quotationRepository
    ) {}

    public function getPaginatedQuotations(int $perPage = 15, ?int $customerId = null): LengthAwarePaginator
    {
        $query = $this->quotationRepository->withRelations();

        if ($customerId) {
            $query->filterByCustomer($customerId);
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
            $quotationData = array_diff_key($data, array_flip(['items', 'adiwarnas', 'clients']));
            $quotation = $this->quotationRepository->update($id, $quotationData);

            if (isset($data['items'])) {
                $quotation->items()->delete();
                foreach ($data['items'] as $item) {
                    $quotation->items()->create($item);
                }
            }

            if (isset($data['adiwarnas'])) {
                $quotation->adiwarnas()->delete();
                foreach ($data['adiwarnas'] as $adiwarna) {
                    $quotation->adiwarnas()->create($adiwarna);
                }
            }

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
