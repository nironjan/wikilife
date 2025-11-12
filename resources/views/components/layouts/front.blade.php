<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <!-- In your layout file -->

        {!! app('meta')->toHtml() !!}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {!! header_scripts() !!}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @stack('scripts')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Navigation Section -->
        <livewire:front.navigation />
        {{ $slot }}
        <!-- Footer Section -->
        <livewire:front.footer-section lazy="on-load" />
        {!! footer_scripts() !!}
    </body>
</html>

