<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepositoryInterface $companyRepository){
        $this->middleware('auth');
    }

    public function index()
    {
        $companies = $this->companyRepository->paginate(perPage: 10);

        return view('company.index', compact('companies'));
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        // Store a newly created company in storage
    }

    public function show(Company $company)
    {
        // Display the specified company
    }

    public function edit(Company $company)
    {
        // Show the form for editing the specified company
    }

    public function update(Request $request, Company $company)
    {
        // Update the specified company in storage
    }

    public function destroy(Company $company)
    {
        // Remove the specified company from storage
    }
} 