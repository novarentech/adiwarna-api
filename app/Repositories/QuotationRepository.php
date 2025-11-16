<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuotationRepositoryInterface;
use App\Models\Quotation;

class QuotationRepository extends BaseRepository implements QuotationRepositoryInterface
{
    protected function model(): string
    {
        return Quotation::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['customer', 'items', 'adiwarnas', 'clients']);
        return $this;
    }

    public function filterByCustomer(int $customerId): self
    {
        $this->query->where('customer_id', $customerId);
        return $this;
    }

    public function filterByDateRange(string $startDate, string $endDate): self
    {
        $this->query->whereBetween('date', [$startDate, $endDate]);
        return $this;
    }
}
