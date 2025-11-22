<?php

namespace App\Contracts\Repositories;

interface QuotationRepositoryInterface extends RepositoryInterface
{
    public function withRelations(): self;
    public function withCustomerOnly(): self;
    public function search(?string $search): self;
    public function filterByCustomer(int $customerId): self;
    public function filterByDateRange(string $startDate, string $endDate): self;
}
