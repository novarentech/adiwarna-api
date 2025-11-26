<?php

namespace App\Contracts\Repositories;

interface PurchaseOrderRepositoryInterface extends RepositoryInterface
{
    public function withRelations(): self;
    public function withCustomerOnly(): self;
    public function filterByCustomer(int $customerId): self;
    public function filterByDateRange(string $startDate, string $endDate): self;
    public function search(string $keyword): self;
}
