<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct()
    {
        $this->model = $this->makeModel();
        $this->query = $this->model->newQuery();
    }

    /**
     * Specify Model class name
     */
    abstract protected function model(): string;

    /**
     * Make Model instance
     */
    protected function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * Reset query builder
     */
    protected function resetQuery(): void
    {
        $this->query = $this->model->newQuery();
    }

    public function all(): Collection
    {
        $result = $this->query->get();
        $this->resetQuery();
        return $result;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $result = $this->query->paginate($perPage);
        $this->resetQuery();
        return $result;
    }

    public function find(int $id): ?Model
    {
        $result = $this->query->find($id);
        $this->resetQuery();
        return $result;
    }

    public function findOrFail(int $id): Model
    {
        $result = $this->query->findOrFail($id);
        $this->resetQuery();
        return $result;
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    public function with(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    public function where(string $column, mixed $value): self
    {
        $this->query->where($column, $value);
        return $this;
    }
}
