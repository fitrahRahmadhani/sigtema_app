<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
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
