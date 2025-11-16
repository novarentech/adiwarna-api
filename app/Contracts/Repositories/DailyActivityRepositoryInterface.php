<?php

namespace App\Contracts\Repositories;

interface DailyActivityRepositoryInterface extends RepositoryInterface
{
    public function withRelations(): self;
    public function filterByUser(int $userId): self;
}
