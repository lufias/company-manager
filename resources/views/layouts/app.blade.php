<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Manager</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        .card-wrapper {
            position: relative;
            padding-top: 20px;
            padding-right: 20px;
            padding-left: 20px;
        }

        .card-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 20vh;
            /* height of the orange top part */
            width: 100%;
            background-color: #e15c3d;
            z-index: 0;
        }

        .card {
            position: relative;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            min-height: 80vh;
        }
    </style>

</head>

<body>

<x-layouts.public.header />

    <div class="card-wrapper">
        <div class="card">
            @yield('content')
        </div>
    </div>


    <!-- Empty body section at the bottom -->
    <footer style="height: 60px;"></footer>
</body>

</html>