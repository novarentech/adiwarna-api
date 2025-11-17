<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransmittalDocumentRepositoryInterface;
use App\Models\TransmittalDocument;

class TransmittalDocumentRepository extends BaseRepository implements TransmittalDocumentRepositoryInterface
{
    protected function model(): string
    {
        return TransmittalDocument::class;
    }

    public function deleteByTransmittalId(int $transmittalId): bool
    {
        return $this->model->where('transmittal_id', $transmittalId)->delete();
    }
}
