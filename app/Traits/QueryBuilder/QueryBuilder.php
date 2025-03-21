<?php

namespace App\Traits\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait QueryBuilder
{
    // On how to use this trait, see documentation/backend/traits/query-builder.md

    use HasFilter, HasSearch, HasSort;

    /**
     * Apply all query modifications (filter, search, sort) in one go
     */
    protected function applyQueryModifications(
        Builder $query,
        Request $request,
        array $filterableFields = [],
        array $searchableFields = [],
        array $sortableFields = [],
        string $defaultSortBy = '',
        string $defaultSortOrder = 'asc',
        int $perPage = 10,

    ) {
        return $this->filter($query, $filterableFields)
            ->when($request->filled('search'), function ($query) use ($request, $searchableFields) {
                return $this->search($query, $request->search, $searchableFields);
            })
            ->when($request->input('sortBy', $defaultSortBy), function ($query) use ($request, $sortableFields, $defaultSortBy, $defaultSortOrder) {
                return $this->sort(
                    $query,
                    $request->input('sortBy', $defaultSortBy),
                    $request->input('sortOrder', $defaultSortOrder),
                    $sortableFields
                );
            })
            ->paginate($request->input('perPage', $perPage));
    }
}
