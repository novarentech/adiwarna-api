<?php

namespace App\Services;

use App\Contracts\Repositories\AboutRepositoryInterface;
use App\Models\About;
use Illuminate\Database\Eloquent\Collection;

class AboutService extends BaseService
{
    public function __construct(
        protected AboutRepositoryInterface $aboutRepository
    ) {}

    public function getAllAbout(): Collection
    {
        return $this->aboutRepository->all();
    }

    public function getActiveAbout(): ?About
    {
        return $this->aboutRepository->getActive();
    }

    public function getAboutById(int $id): ?About
    {
        return $this->aboutRepository->find($id);
    }

    public function createAbout(array $data): About
    {
        return $this->executeInTransaction(function () use ($data) {
            // If is_active is true, deactivate others
            if (isset($data['is_active']) && $data['is_active']) {
                About::query()->update(['is_active' => false]);
            }

            return $this->aboutRepository->create($data);
        });
    }

    public function updateAbout(int $id, array $data): About
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            // If is_active is true, deactivate others
            if (isset($data['is_active']) && $data['is_active']) {
                About::query()->where('id', '!=', $id)->update(['is_active' => false]);
            }

            return $this->aboutRepository->update($id, $data);
        });
    }

    public function deleteAbout(int $id): bool
    {
        return $this->aboutRepository->delete($id);
    }

    public function setActive(int $id): bool
    {
        return $this->aboutRepository->setActive($id);
    }
}
