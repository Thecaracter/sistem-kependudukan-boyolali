<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Simple Favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-secondary-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>
