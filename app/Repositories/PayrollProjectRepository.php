<?php

namespace App\Repositories;

use App\Contracts\Repositories\PayrollProjectRepositoryInterface;
use App\Models\PayrollProject;

class PayrollProjectRepository extends BaseRepository implements PayrollProjectRepositoryInterface
{
    protected function model(): string
    {
        return PayrollProject::class;
    }

    public function withPeriods(): self
    {
        $this->query->with('periods');
        return $this;
    }

    public function search(?string $search = null): self
    {
        if ($search) {
            $this->query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        return $this;
    }
}
