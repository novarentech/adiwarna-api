<?php

namespace App\Repositories;

use App\Contracts\Repositories\OperationalRepositoryInterface;
use App\Models\Operational;
use Illuminate\Database\Eloquent\Collection;

class OperationalRepository extends BaseRepository implements OperationalRepositoryInterface
{
    protected function model(): string
    {
        return Operational::class;
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function byType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
}
