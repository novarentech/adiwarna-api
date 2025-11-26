<?php

namespace App\Contracts\Repositories;

interface TransmittalDocumentRepositoryInterface extends RepositoryInterface
{
    /**
     * Delete documents by transmittal ID
     */
    public function deleteByTransmittalId(int $transmittalId): bool;
}
