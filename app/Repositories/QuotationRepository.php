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

    public function withCustomerOnly(): self
    {
        $this->query->with(['customer']);
        return $this;
    }

    public function search(?string $search): self
    {
        if ($search) {
            $this->query->where(function ($query) use ($search) {
                $query->where('pic_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
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

    public function sortBy(string $sortOrder = 'desc'): self
    {
        $this->query->orderBy('ref_year', $sortOrder)
            ->orderBy('ref_no', $sortOrder);
        return $this;
    }
}
