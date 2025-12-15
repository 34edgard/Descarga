<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait PaginateAndFilterTrait
{
    /**
     * Get paginated data with filters.
     *
     * @param Model|Builder $modelOrBuilder
     * @param array $filters
     * @param int $perPage
     * @param array $searchFields
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allWithPaginate(
        $modelOrBuilder,
        array $filters = [],
        int $perPage = 10,
        array $searchFields = []
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        $query = $modelOrBuilder instanceof Builder
            ? $modelOrBuilder
            : $modelOrBuilder->query();

        return $query
            ->when(
                isset($filters['search']) && !empty($searchFields),
                function (Builder $query) use ($filters, $searchFields) {
                    $query->where(function (Builder $q) use ($filters, $searchFields) {
                        foreach ($searchFields as $field) {
                            // Manejar relaciones con notación punto
                            if (str_contains($field, '.')) {
                                [$relation, $column] = explode('.', $field);
                                $q->orWhereHas($relation, function ($q) use ($column, $filters) {
                                    $q->where($column, 'like', '%' . $filters['search'] . '%');
                                });
                            } else {
                                $q->orWhere($field, 'like', '%' . $filters['search'] . '%');
                            }
                        }
                    });
                }
            )
            ->when(isset($filters['sort_by']), function (Builder $query) use ($filters) {
                $query->orderBy(
                    $filters['sort_by'],
                    $filters['sort_direction'] ?? 'asc'
                );
            })
            ->paginate($filters['per_page'] ?? $perPage);
    }
}
