<?php

namespace App\Traits\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait HasSort
{
    /**
     * Apply sorting to the query
     */
    protected function sort(Builder $query, string $sortBy, string $sortOrder, array $sortableFields): Builder
    {
        if (!isset($sortableFields[$sortBy])) {
            throw new InvalidArgumentException("Invalid sort field: {$sortBy}");
        }

        $config = $sortableFields[$sortBy];
        $baseTable = $query->getModel()->getTable();

        // Ensure we're selecting from the base table
        $query->select("{$baseTable}.*");

        // Apply joins if defined
        if (isset($config['joins'])) {
            foreach ($config['joins'] as $join) {
                $this->applyJoin($query, $join);
            }
        }

        // Apply sorting
        // $sortColumn = $config['column'] ?? "{$baseTable}.{$sortBy}";
        $sortColumn = $config['column'] ?? "{$baseTable}.{$config}";
        return $query->orderBy($sortColumn, strtolower($sortOrder));
    }


    /**
     * Apply join to query
     */
    private function applyJoin(Builder $query, array $join): void
    {
        $table = $join[0];
        $first = $join[1];
        $operator = $join[2] ?? '=';
        $second = $join[3];
        $type = $join['type'] ?? 'left';
        $morphType = $join['morph_type'] ?? null;
        $morphClass = $join['morph_class'] ?? null;

        // Skip if join already exists
        if ($this->joinExists($query, $table)) {
            return;
        }

        $method = "{$type}Join";
        $query->{$method}($table, function ($join) use ($first, $operator, $second, $morphType, $morphClass) {
            $join->on($first, $operator, $second);

            if ($morphType && $morphClass) {
                $join->where($morphType, '=', $morphClass);
            }
        });
    }

    /**
     * Check if join already exists in query
     */
    private function joinExists(Builder $query, string $table): bool
    {
        return collect($query->getQuery()->joins)->pluck('table')->contains($table);
    }
}
