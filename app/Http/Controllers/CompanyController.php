<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CompanyRepositoryInterface $companyRepository){
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->companyRepository->paginate(perPage: 10);

        return view('company.index', compact('companies'));
    }

    public function create()
    {
        $this->authorize('create', Company::class);

        return view('company.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        // validate the request
        $request->validate([
            'name' => 'required|unique:companies|max:255',
            'email' => 'nullable|email|unique:companies',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'website' => 'nullable|url|unique:companies',
        ]);

        $this->companyRepository->create($request->all());

        return redirect()->route('company.index')->with('success', 'Company created successfully');
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return view('company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        return view('company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        // validate the request
        $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id . '|max:255',
            'email' => 'nullable|email|unique:companies,email,' . $company->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'website' => 'nullable|url|unique:companies,website,' . $company->id,
        ]);

        $this->companyRepository->update($company, $request->all());

        return redirect()->route('company.index')->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $this->companyRepository->delete($company);

        return redirect()->route('company.index')->with('success', 'Company deleted successfully');
    }
} 