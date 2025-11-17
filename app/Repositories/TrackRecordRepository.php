<?php

namespace App\Repositories;

use App\Contracts\Repositories\TrackRecordRepositoryInterface;
use App\Models\TrackRecord;
use Illuminate\Database\Eloquent\Collection;

class TrackRecordRepository extends BaseRepository implements TrackRecordRepositoryInterface
{
    protected function model(): string
    {
        return TrackRecord::class;
    }

    public function withCustomer(): self
    {
        $this->query->with('customer');
        return $this;
    }

    public function byCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function byStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }
}
