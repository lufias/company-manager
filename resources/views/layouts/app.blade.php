<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Manager</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body>

<x-layouts.header />

    <div class="relative pt-5 pr-5 pl-5">
        <div class="absolute top-0 left-0 h-[30vh] w-full bg-primary-orange z-0"></div>
        <div class="relative bg-white p-5 shadow-lg z-10 rounded-t-lg min-h-[80vh]">
            @yield('content')
        </div>
    </div>

    <!-- Empty body section at the bottom -->
    <footer class="h-[60px]"></footer>
</body>

</html>