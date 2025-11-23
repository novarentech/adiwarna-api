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
        $this->query->with(['customer', 'members.employee', 'descriptions']);
        return $this;
    }

    public function withCustomerOnly(): self
    {
        $this->query->with(['customer']);
        return $this;
    }

    public function filterByUser(int $userId): self
    {
        // Implement role-based filtering if needed
        // For now, just return self
        return $this;
    }

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->whereHas('customer', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })
                ->orWhere('location', 'like', "%{$keyword}%")
                ->orWhere('prepared_name', 'like', "%{$keyword}%");
        });
        return $this;
    }
}
