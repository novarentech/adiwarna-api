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

    public function withItemsCount(): self
    {
        $this->query->withCount('items');
        return $this;
    }

    public function search(?string $search = null): self
    {
        if ($search) {
            $this->query->where(function ($query) use ($search) {
                $query->where('po_inv_pr_no', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhere('received_by', 'like', "%{$search}%");
            });
        }
        return $this;
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('receiving_date', [$startDate, $endDate])
            ->orderBy('receiving_date', 'desc')
            ->get();
    }
}
