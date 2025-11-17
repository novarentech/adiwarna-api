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

    public function getPaginatedTransmittals(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transmittalRepository->withRelations()->paginate($perPage);
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
                $transmittal->documents()->delete();
                foreach ($data['documents'] as $document) {
                    $transmittal->documents()->create($document);
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
