<?php

namespace App\Repositories;

use App\Contracts\Repositories\DeliveryNoteRepositoryInterface;
use App\Models\DeliveryNote;
use Illuminate\Database\Eloquent\Collection;

class DeliveryNoteRepository extends BaseRepository implements DeliveryNoteRepositoryInterface
{
    protected function model(): string
    {
        return DeliveryNote::class;
    }

    public function withItems(): self
    {
        $this->query->with(['items', 'customer']);
        return $this;
    }

    public function withItemsCount(): self
    {
        $this->query->withCount('items')->with(['customer']);
        return $this;
    }

    public function search(?string $search = null): self
    {
        if ($search) {
            $this->query->where(function ($query) use ($search) {
                $query->where('dn_no', 'like', "%{$search}%")
                    ->orWhere('wo_no', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }
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
