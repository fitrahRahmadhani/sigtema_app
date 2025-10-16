<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    {{-- class="dark" --}}
    {{-- Aktifkan class="dark" jika ingin menggunakan dark mode --}}
>
    <head>
        @include('partials.head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-primary-gray min-h-screen">
        <!-- Start Sidebar Dashboard -->
        <x-layouts.app.sidebar />
        <!-- End Sidebar Dashboard-->

        <!-- Start Header Dashboard -->
        <x-layouts.app.header />
        <!-- End Header Dashboard-->

        {{ $slot }}

        @fluxScripts
        <x-toaster-hub />
    </body>
</html>
