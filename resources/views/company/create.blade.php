@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <!-- Header with orange background -->
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-medium">Create Company</h1>
            <button type="submit" form="company-form" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-md font-medium transition-colors">
                Save
            </button>
        </div>

        <!-- Form content -->
        <div class="p-6">
            <form id="company-form" action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Section header -->
                <h2 class="text-gray-400 text-sm font-medium uppercase tracking-wide mb-6">COMPANY INFORMATION</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name field -->
                    <div>
                        <label for="name" class="block text-gray-600 text-sm font-medium mb-2">Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value=""
                               placeholder="Company Name"
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent" 
                               required>
                    </div>

                    <!-- Email field -->
                    <div>
                        <label for="email" class="block text-gray-600 text-sm font-medium mb-2">Email address</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value=""
                               placeholder="Email Address"
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    </div>

                    <!-- Logo field -->
                    <div>
                        <label for="logo" class="block text-gray-600 text-sm font-medium mb-2">Logo</label>
                        <div class="flex">
                            <label for="logo" class="bg-gray-100 border border-gray-200 rounded-l-md px-3 py-2 text-gray-600 cursor-pointer hover:bg-gray-200 transition-colors">
                                Choose File
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   class="hidden">
                            <div class="flex-1 px-3 py-2 bg-gray-100 border-t border-r border-b border-gray-200 rounded-r-md text-gray-600">
                                Choose File
                            </div>
                        </div>
                    </div>

                    <!-- Website field -->
                    <div>
                        <label for="website" class="block text-gray-600 text-sm font-medium mb-2">Website</label>
                        <input type="url" 
                               name="website" 
                               id="website" 
                               value="test.com"
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle file input display
        document.getElementById('logo').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '57_by_gydw1n_dbtfbmw.jpg';
            const display = this.parentElement.querySelector('div');
            display.textContent = fileName;
        });
    </script>
@endsection
