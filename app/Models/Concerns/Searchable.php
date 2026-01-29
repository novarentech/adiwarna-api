<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Searchable Trait
 * 
 * Provides basic search functionality to Models.
 * Can be customized per-model by overriding searchableColumns() method.
 */
trait Searchable
{
    /**
     * Scope: Search across searchable columns
     * 
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        $columns = $this->searchableColumns();

        if (empty($columns)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$keyword}%");
            }
        });
    }

    /**
     * Define searchable columns for this model
     * Override this method in your model to customize search columns
     * 
     * @return array
     */
    protected function searchableColumns(): array
    {
        // Default: return fillable columns
        // Models can override this method to specify exact columns
        return $this->fillable ?? [];
    }
}
