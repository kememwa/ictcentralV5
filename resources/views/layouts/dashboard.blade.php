<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/kimfay.png') }}" type="image/png">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">

    {{-- Shared Alpine state for sidebar toggle (mobile) --}}
    <div x-data="{ sidebarOpen: window.matchMedia('(min-width: 640px)').matches }" x-cloak>

        {{-- Sidebar (fixed, left) --}}
        @livewire('sidebar')

        {{-- Mobile backdrop --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-slate-900/40 backdrop-blur-sm sm:hidden"
        ></div>

        {{-- Navbar (fixed, top — offset for sidebar on desktop) --}}
        @livewire('Navigationbar')

        {{-- Toast notifications --}}
        @livewire('notification-toast')

        {{-- Main content area --}}
        <main class="min-h-screen pt-16 sm:pl-64">
            <div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
