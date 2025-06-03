@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <!-- Header with orange background -->
    <div class="px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-medium">Company Details</h1>
        <div class="flex gap-2">
            @can('update', $company)
                <x-ui.button href="{{ route('company.edit', $company) }}">Edit</x-ui.button>
            @endcan
            <a href="{{ route('company.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <!-- Company content -->
    <div class="p-6">
        <!-- Section header -->
        <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wide mb-6">COMPANY INFORMATION</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Logo -->
            <div class="md:col-span-2 flex justify-center mb-6">
                <x-company.logo :company="$company" />
            </div>

            <!-- Name field -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Name</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    {{ $company->name }}
                </div>
            </div>

            <!-- Email field -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Email address</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    {{ $company->email ?: 'Not provided' }}
                </div>
            </div>

            <!-- Website field -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Website</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    @if($company->website)
                        <a href="{{ $company->website }}" class="text-blue-600 hover:underline" target="_blank" rel="noopener">
                            {{ $company->website }}
                        </a>
                    @else
                        Not provided
                    @endif
                </div>
            </div>

            <!-- Created by -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Created by</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    {{ $company->creator->name }}
                </div>
            </div>

            <!-- Created at -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Created at</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    {{ $company->created_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>

            <!-- Updated at -->
            <div>
                <label class="block text-gray-600 text-sm font-medium mb-2">Last updated</label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md text-gray-700">
                    {{ $company->updated_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 