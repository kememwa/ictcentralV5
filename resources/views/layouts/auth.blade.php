<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('images/kimfay.png') }}" type="image/png">

        <title>{{ config('app.name') }}</title>

        <style>[x-cloak] { display: none !important; }</style>

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-gray-50 antialiased">

        {{-- Toast notifications --}}
        @livewire('notification-toast')

        <div class="min-h-screen flex items-center justify-center">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>