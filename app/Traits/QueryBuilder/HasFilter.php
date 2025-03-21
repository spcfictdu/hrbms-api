<?php

namespace App\Traits\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    /**
     * Apply filters to the query
     */
    protected function filter(Builder $query, array $filterableFields): Builder
    {
        foreach ($filterableFields as $requestKey => $field) {
            if (!request()->has($requestKey)) {
                continue;
            }

            $values = $this->parseFilterValues(request($requestKey));

            if (is_array($field)) {
                // Handle OR conditions across multiple fields
                $query->where(function ($query) use ($field, $values) {
                    foreach ($field as $orField) {
                        $this->applyFieldFilter($query, $orField, $values, 'or');
                    }
                });
            } else {
                $this->applyFieldFilter($query, $field, $values);
            }
        }

        return $query;
    }

    /**
     * Parse filter values from request
     */
    private function parseFilterValues($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        return str_contains($value, ',') ? explode(',', $value) : [$value];
    }

    /**
     * Apply filter for a specific field
     */
    private function applyFieldFilter(Builder $query, string $field, array $values, string $boolean = 'and'): void
    {
        if (str_contains($field, '.')) {
            // Handle relationship filters
            [$relation, $column] = $this->parseRelationField($field);
            $method = $boolean === 'or' ? 'orWhereHas' : 'whereHas';

            $query->{$method}($relation, function ($q) use ($column, $values) {
                count($values) === 1
                    ? $q->where($column, $values[0])
                    : $q->whereIn($column, $values);
            });
        } else {
            // Handle direct column filters
            count($values) === 1
                ? $query->where($field, $values[0], boolean: $boolean)
                : $query->whereIn($field, $values, boolean: $boolean);
        }
    }
}
