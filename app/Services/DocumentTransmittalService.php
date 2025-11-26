<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentTransmittalRepositoryInterface;
use App\Contracts\Repositories\TransmittalDocumentRepositoryInterface;
use App\Models\DocumentTransmittal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentTransmittalService extends BaseService
{
    public function __construct(
        protected DocumentTransmittalRepositoryInterface $transmittalRepository,
        protected TransmittalDocumentRepositoryInterface $documentRepository
    ) {}

    public function getPaginatedTransmittals(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->transmittalRepository->withCustomerOnly();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getTransmittalById(int $id): ?DocumentTransmittal
    {
        return $this->transmittalRepository->withRelations()->find($id);
    }

    public function createTransmittalWithDocuments(array $data): DocumentTransmittal
    {
        return $this->executeInTransaction(function () use ($data) {
            $transmittalData = array_diff_key($data, array_flip(['documents']));
            $transmittal = $this->transmittalRepository->create($transmittalData);

            if (isset($data['documents'])) {
                foreach ($data['documents'] as $document) {
                    $transmittal->documents()->create($document);
                }
            }

            return $transmittal->load('documents', 'customer');
        });
    }

    public function updateTransmittalWithDocuments(int $id, array $data): DocumentTransmittal
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $transmittalData = array_diff_key($data, array_flip(['documents']));
            $transmittal = $this->transmittalRepository->update($id, $transmittalData);

            if (isset($data['documents'])) {
                // Get existing document IDs from request
                $requestDocumentIds = collect($data['documents'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Delete documents that are not in the request
                $transmittal->documents()
                    ->whereNotIn('id', $requestDocumentIds)
                    ->delete();

                // Update or create documents
                foreach ($data['documents'] as $documentData) {
                    if (isset($documentData['id'])) {
                        // Update existing document
                        $transmittal->documents()
                            ->where('id', $documentData['id'])
                            ->update(array_diff_key($documentData, array_flip(['id'])));
                    } else {
                        // Create new document
                        $transmittal->documents()->create($documentData);
                    }
                }
            }

            return $transmittal->load('documents', 'customer');
        });
    }

    public function deleteTransmittal(int $id): bool
    {
        return $this->transmittalRepository->delete($id);
    }
}
