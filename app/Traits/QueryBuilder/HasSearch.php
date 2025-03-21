<?php

namespace App\Traits\QueryBuilder;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


trait HasSearch
{
    /**
     * Apply search to the query with enhanced date handling
     */
    protected function search(Builder $query, string $searchTerm, array $searchableFields): Builder
    {
        return $query->where(function ($query) use ($searchTerm, $searchableFields) {
            foreach ($searchableFields as $field => $config) {
                // Handle field configuration
                if (is_array($config)) {
                    // If fielt is not given, use the key as field name
                    $fieldName = $config['field'] ?? $field;
                    $fieldType = $config['type'] ?? 'string';
                } else {
                    $fieldName = $config;
                    $fieldType = 'string';
                }

                // Handle different field types
                switch ($fieldType) {
                    case 'date':
                        $this->applyDateSearch($query, $fieldName, $searchTerm);
                        break;
                    default:

                        $this->applyFieldSearch($query, $fieldName, $searchTerm);
                }
            }
        });
    }

    /**
     * Apply date specific search
     */
    private function applyDateSearch(Builder $query, string $field, string $searchTerm): void
    {
        // Try to identify the type of partial date
        $dateInfo = $this->analyzeDateSearch($searchTerm);

        // dd($dateInfo);


        if ($dateInfo) {
            if (str_contains($field, '.')) {
                [$relation, $column] = $this->parseRelationField($field);
                $query->orWhereHas($relation, function ($q) use ($column, $dateInfo) {
                    $this->buildDateCondition($q, $column, $dateInfo);
                });
            } else {
                $this->buildDateCondition($query, $field, $dateInfo, 'or');
            }
        }
    }

    private function analyzeDateSearch(string $searchTerm): ?array
    {
        $searchTerm = trim($searchTerm);

        // Check for year only (2024, 24)
        if (preg_match('/^(\d{2}|\d{4})$/', $searchTerm)) {
            $year = strlen($searchTerm) == 2 ? '20' . $searchTerm : $searchTerm;
            return ['type' => 'year', 'year' => $year];
        }

        // Check for month name only (January, Jan)
        if (preg_match('/^[A-Za-z]+$/', $searchTerm)) {
            try {
                $date = Carbon::createFromFormat('F', $searchTerm) ?? Carbon::createFromFormat('M', $searchTerm);
                return ['type' => 'month', 'month' => $date->month];
            } catch (\Exception $e) {
                return null;
            }
        }

        // Check for full date (01/24/2024, January 24, 2024, Jan 24, 2024, january 27, 2025)
        if (preg_match('/^([A-Za-z]+|\d{1,2})[\/\s-]+(\d{1,2}),?\s+(\d{4})$/', $searchTerm)) {
            try {
                $date = Carbon::createFromFormat('F j, Y', $searchTerm) ?? // January 27, 2025
                    Carbon::createFromFormat('M j, Y', $searchTerm) ?? // Jan 27, 2025
                    Carbon::createFromFormat('m/d/Y', $searchTerm) ??  // 01/27/2025
                    Carbon::createFromFormat('m-d-Y', $searchTerm);    // 01-27-2025
                return [
                    'type' => 'full_date',
                    'month' => $date->month,
                    'day' => $date->day,
                    'year' => $date->year
                ];
            } catch (\Exception $e) {
                return null;
            }
        }

        // Check for month number only (01-12)
        if (preg_match('/^(0?[1-9]|1[0-2])$/', $searchTerm)) {
            return ['type' => 'month', 'month' => (int)$searchTerm];
        }

        // Check for month and year (01/2024, January 2024, Jan 2024)
        if (preg_match('/^([A-Za-z]+|\d{1,2})[\/\s-]+(\d{4})$/', $searchTerm)) {
            try {
                $date = Carbon::parse("1 $searchTerm");
                return [
                    'type' => 'month_year',
                    'month' => $date->month,
                    'year' => $date->year
                ];
            } catch (\Exception $e) {
                return null;
            }
        }

        // Check for month and day (January 24, Jan 24)
        if (preg_match('/^([A-Za-z]+|\d{1,2})[\/\s-]+(\d{1,2})$/', $searchTerm)) {
            try {
                $date = Carbon::parse($searchTerm);
                return [
                    'type' => 'month_day',
                    'month' => $date->month,
                    'day' => $date->day
                ];
            } catch (\Exception $e) {
                return null;
            }
        }

        // Check for day and month (24 January, 24/01)
        if (preg_match('/^(\d{1,2})[\/\s-]+([A-Za-z]+|\d{1,2})$/', $searchTerm)) {
            try {
                $date = Carbon::parse($searchTerm);
                return [
                    'type' => 'day_month',
                    'day' => $date->day,
                    'month' => $date->month
                ];
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    private function buildDateCondition(Builder $query, string $field, array $dateInfo, string $boolean = 'and'): void
    {
        switch ($dateInfo['type']) {
            case 'year':
                // Filter by year
                $query->whereRaw("YEAR({$field}) = ?", [$dateInfo['year']], $boolean);
                break;

            case 'month':
                // Filter by month
                $query->whereRaw("MONTH({$field}) = ?", [$dateInfo['month']], $boolean);
                break;

            case 'month_year':
                // Filter by month and year
                $query->whereRaw(
                    "YEAR({$field}) = ? AND MONTH({$field}) = ?",
                    [$dateInfo['year'], $dateInfo['month']],
                    $boolean
                );
                break;

            case 'month_day':
                // Filter by month and day
                $query->whereRaw(
                    "MONTH({$field}) = ? AND DAY({$field}) = ?",
                    [$dateInfo['month'], $dateInfo['day']],
                    $boolean
                );
                break;

            case 'day_month':
                // Filter by day and month
                $query->whereRaw(
                    "MONTH({$field}) = ? AND DAY({$field}) = ?",
                    [$dateInfo['month'], $dateInfo['day']],
                    $boolean
                );
                break;

            case 'full_date':
                // Filter by full date (year, month, day)
                $date = Carbon::create($dateInfo['year'], $dateInfo['month'], $dateInfo['day']);
                $query->whereRaw(
                    "DATE({$field}) = ?",
                    [$date->format('Y-m-d')],
                    $boolean
                );
                break;

            default:
                // Handle unknown types (optional)
                throw new \InvalidArgumentException("Unknown date type: {$dateInfo['type']}");
        }
    }

    /**
     * Try to parse a date string using multiple formats
     */
    private function parseDateFromMultipleFormats(string $dateString): ?Carbon
    {
        // Remove any extra whitespace
        $dateString = trim($dateString);

        // Try each format
        foreach ($this->dateSearchConfig['formats'] as $format) {
            try {
                return Carbon::createFromFormat($format, $dateString);
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try natural language parsing as fallback
        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Apply search for a specific field
     */
    private function applyFieldSearch(Builder $query, string $field, string $searchTerm, string $boolean = 'or'): void
    {
        if (str_contains($field, '.')) {
            // Handle relationship search
            [$relation, $column] = $this->parseRelationField($field);
            $method = $boolean === 'or' ? 'orWhereHas' : 'whereHas';

            $query->{$method}($relation, function ($q) use ($column, $searchTerm) {
                $q->where($column, 'LIKE', "%{$searchTerm}%");
            });
        } else {
            // Handle direct column search
            $query->where($field, 'LIKE', "%{$searchTerm}%", $boolean);
        }
    }

    /**
     * Parse relation and field from dot notation
     */
    private function parseRelationField(string $field): array
    {
        $segments = explode('.', $field);
        $column = array_pop($segments);
        $relation = implode('.', $segments);

        return [$relation, $column];
    }
}
