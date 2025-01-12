<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'SIDESPIN'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col">
            @include('components.header')

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            @include('components.footer')
        </div>
    </div>
    @stack('scripts')
</body>

</html>
