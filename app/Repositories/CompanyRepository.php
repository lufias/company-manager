<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function create(array $data)
    {
        $company = new Company();
        $company->name = $data['name'];
        $company->email = $data['email'];

        // Handle logo upload
        if (isset($data['logo']) && $data['logo']) {
            $logoPath = $data['logo']->store('companies', 'public');
            $company->logo = $logoPath;
        }

        $company->website = $data['website'];
        $company->created_by = Auth::user()->id;
        $company->save();

        return $company;
    }
}