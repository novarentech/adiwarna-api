<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentTransmittalRepositoryInterface;
use App\Models\DocumentTransmittal;
use Illuminate\Database\Eloquent\Collection;

class DocumentTransmittalRepository extends BaseRepository implements DocumentTransmittalRepositoryInterface
{
    protected function model(): string
    {
        return DocumentTransmittal::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['documents', 'customer']);
        return $this;
    }

    public function byCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
}
