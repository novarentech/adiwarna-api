<?php

namespace App\Repositories;

use App\Contracts\Repositories\DailyActivityRepositoryInterface;
use App\Models\DailyActivity;

class DailyActivityRepository extends BaseRepository implements DailyActivityRepositoryInterface
{
    protected function model(): string
    {
        return DailyActivity::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['customer', 'members', 'descriptions']);
        return $this;
    }

    public function filterByUser(int $userId): self
    {
        // Implement role-based filtering if needed
        // For now, just return self
        return $this;
    }
}
