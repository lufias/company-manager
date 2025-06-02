@extends('layouts.app')

@section('content')
    <h1 class="font-semibold text-gray-500 mb-4">Companies</h1>
    <x-ui.button href="{{ route('company.create') }}">Add New Company</x-ui.button>

    <div class="my-4 bg-white rounded-xl shadow p-6">
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-bold text-gray-400 uppercase pb-4">Name</th>
                        <th class="text-left text-xs font-bold text-gray-400 uppercase pb-4">Email</th>
                        <th class="text-left text-xs font-bold text-gray-400 uppercase pb-4">Website</th>
                        <th class="text-left text-xs font-bold text-gray-400 uppercase pb-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)

                        <tr class="align-middle border-t border-transparent hover:bg-gray-50 transition">
                            <td class="py-4 font-medium text-gray-700 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="" class="w-12 h-12 rounded-lg object-cover shadow-sm" />
                                {{ $company->name }}
                            </td>
                            <td class="py-4 text-gray-600">{{ $company->email }}</td>
                            <td class="py-4 text-gray-600">
                                <a href="{{ $company->website }}" class="text-blue-600 hover:underline" target="_blank" rel="noopener">{{ $company->website }}</a>
                            </td>
                            <td class="py-4 text-gray-600">
                                <a href="#" class="text-blue-600 hover:underline mr-2">Edit</a>
                                <a href="#" class="text-red-600 hover:underline">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $companies->links() }}
@endsection