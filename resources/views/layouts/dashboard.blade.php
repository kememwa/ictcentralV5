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
        @livewire('SideBar')

        {{-- Mobile backdrop --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-slate-900/40 backdrop-blur-sm sm:hidden"
        ></div>

        {{-- Navbar (fixed, top — offset for sidebar on desktop) --}}
        @livewire('NavigationBar')

        {{-- Toast notifications --}}
        @livewire('notification-toast')

        {{-- Main content area --}}
        <main class="min-h-screen pt-16 sm:pl-64">
            <div
                x-data="{ loading: false }"
                x-on:livewire:navigate.window="
                    loading = true;
                    if (window.innerWidth < 640) {
                        sidebarOpen = false;
                    }
                "
                x-on:livewire:navigated.window="loading = false"
                class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">

            <div class="relative min-h-[200px]">

                        <!-- Loading Screen -->
                    <div
                        x-show="loading"
                        x-transition.opacity.duration.50ms
                        class="fixed top-16 left-0 right-0 bottom-0 sm:left-64 z-50"
                        x-cloak
                    >
                        <div class="flex h-full w-full items-center justify-center">
                            <div class="flex flex-col items-center space-y-4">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 200 200"
                                    class="h-20 w-20"
                                >
                                    <radialGradient
                                        id="spinner-gradient"
                                        cx=".66"
                                        fx=".66"
                                        cy=".3125"
                                        fy=".3125"
                                        gradientTransform="scale(1.5)"
                                    >
                                        <stop offset="0" stop-color="#2563EB"/>
                                        <stop offset=".3" stop-color="#2563EB" stop-opacity=".9"/>
                                        <stop offset=".6" stop-color="#2563EB" stop-opacity=".6"/>
                                        <stop offset=".8" stop-color="#2563EB" stop-opacity=".3"/>
                                        <stop offset="1" stop-color="#2563EB" stop-opacity="0"/>
                                    </radialGradient>

                                    <circle
                                        transform-origin="center"
                                        fill="none"
                                        stroke="url(#spinner-gradient)"
                                        stroke-width="15"
                                        stroke-linecap="round"
                                        stroke-dasharray="200 1000"
                                        cx="100"
                                        cy="100"
                                        r="70"
                                    >
                                        <animateTransform
                                            attributeName="transform"
                                            type="rotate"
                                            dur="2s"
                                            values="360;0"
                                            repeatCount="indefinite"
                                        />
                                    </circle>

                                    <circle
                                        transform-origin="center"
                                        fill="none"
                                        opacity=".2"
                                        stroke="#2563EB"
                                        stroke-width="15"
                                        stroke-linecap="round"
                                        cx="100"
                                        cy="100"
                                        r="70"
                                    />
                                </svg>

                                <p class="text-sm font-medium text-blue-600">
                                    Loading...
                                </p>

                            </div>
                        </div>
                    </div>


                                    <!-- Page Content -->
                    <div x-show="!loading" x-transition.opacity.duration.150ms>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
