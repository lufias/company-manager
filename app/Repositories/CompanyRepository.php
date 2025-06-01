<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function all()
    {
        return Company::all();
    }

    public function paginate($perPage = 15, $orderBy = 'updated_at', $orderDirection = 'desc')
    {
        return Company::orderBy($orderBy, $orderDirection)->paginate($perPage);
    }
}