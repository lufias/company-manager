<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Manager</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])           
    @endif
    
</head>
<body>
    
    <x-layouts.header /> 

    <main style="min-height: 60vh;">
        @yield('content')
    </main>
    <!-- Empty body section at the bottom -->
    <footer style="height: 60px;"></footer>
</body>
</html>
