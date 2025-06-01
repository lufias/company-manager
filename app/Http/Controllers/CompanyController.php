<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('company.index');
    }

    public function create()
    {
        // Show the form for creating a new company
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