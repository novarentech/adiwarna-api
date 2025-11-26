<?php

namespace App\Repositories;

use App\Contracts\Repositories\MaterialReceivingReportRepositoryInterface;
use App\Models\MaterialReceivingReport;
use Illuminate\Database\Eloquent\Collection;

class MaterialReceivingReportRepository extends BaseRepository implements MaterialReceivingReportRepositoryInterface
{
    protected function model(): string
    {
        return MaterialReceivingReport::class;
    }

    public function withItems(): self
    {
        $this->query->with('items');
        return $this;
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
}
