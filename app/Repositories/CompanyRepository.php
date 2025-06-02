<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\FileSystemRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyRepository implements CompanyRepositoryInterface
{
    protected $fileSystemRepository;

    public function __construct(FileSystemRepositoryInterface $fileSystemRepository)
    {
        $this->fileSystemRepository = $fileSystemRepository;
    }

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
            // Ensure the storage directory structure exists
            if (!$this->fileSystemRepository->createModuleStorageStructure('companies')) {
                throw new \Exception('Failed to create storage directory structure for companies');
            }
            
            $logoPath = $data['logo']->store('companies', 'public');
            $company->logo = $logoPath;
        }

        $company->website = $data['website'];
        $company->created_by = Auth::user()->id;
        $company->save();

        return $company;
    }

    public function update(Company $company, array $data)
    {
        $company->name = $data['name'];
        $company->email = $data['email'];

        // Handle logo upload
        if (isset($data['logo']) && $data['logo']) {
            // Delete old logo if it exists and is not a URL
            if ($company->logo && !str_starts_with($company->logo, 'http')) {
                Storage::disk('public')->delete($company->logo);
            }

            // Ensure the storage directory structure exists
            if (!$this->fileSystemRepository->createModuleStorageStructure('companies')) {
                throw new \Exception('Failed to create storage directory structure for companies');
            }

            $logoPath = $data['logo']->store('companies', 'public');
            $company->logo = $logoPath;
        }

        $company->website = $data['website'];
        $company->save();

        return $company;
    }

    public function delete(Company $company)
    {
        // Delete logo file if it exists and is not a URL
        if ($company->logo && !str_starts_with($company->logo, 'http')) {
            Storage::disk('public')->delete($company->logo);
        }

        return $company->delete();
    }
}