<?php

namespace App\Contracts\Repositories;

interface QuotationRepositoryInterface extends RepositoryInterface
{
    public function withRelations(): self;
    public function filterByCustomer(int $customerId): self;
    public function filterByDateRange(string $startDate, string $endDate): self;
}
