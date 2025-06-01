<?php

namespace App\Repositories\Interfaces;

interface CompanyRepositoryInterface
{
    public function all();

    /**
     * Paginate companies with optional ordering.
     *
     * @param int $perPage Number of items per page.
     * @param string $orderBy Column to order by.
     * @param string $orderDirection Direction of ordering ('asc' or 'desc').
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $orderBy = 'updated_at', $orderDirection = 'desc');
}
