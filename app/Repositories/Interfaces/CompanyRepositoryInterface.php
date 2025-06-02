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

    /**
     * Create a new company.
     *
     * @param array $data The data to create the company with.
     * @return \App\Models\Company The created company.
     */
    public function create(array $data);

    /**
     * Update an existing company.
     *
     * @param \App\Models\Company $company The company to update.
     * @param array $data The data to update the company with.
     * @return \App\Models\Company The updated company.
     */
    public function update(\App\Models\Company $company, array $data);

    /**
     * Delete a company.
     *
     * @param \App\Models\Company $company The company to delete.
     * @return bool True if the company was deleted successfully.
     */
    public function delete(\App\Models\Company $company);
}
