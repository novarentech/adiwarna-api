<?php

namespace App\Repositories;

use App\Contracts\Repositories\EquipmentProjectRepositoryInterface;
use App\Models\EquipmentProject;
use Illuminate\Database\Eloquent\Collection;

class EquipmentProjectRepository extends BaseRepository implements EquipmentProjectRepositoryInterface
{
    protected function model(): string
    {
        return EquipmentProject::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['customer', 'customerLocation', 'equipments']);
        return $this;
    }

    public function withCustomerAndLocation(): self
    {
        $this->query->with(['customer', 'customerLocation']);
        return $this;
    }

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->whereHas('customer', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })
                ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                })
                ->orWhere('prepared_by', 'like', "%{$keyword}%")
                ->orWhere('verified_by', 'like', "%{$keyword}%");
        });
        return $this;
    }

    public function byCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }
}
