<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen flex flex-col antialiased bg-primary-gray">
        <header class="h-28 w-full flex justify-center items-center bg-secondary-gray relative z-10">
            <img
                src="{{ asset('assets/logo/sig-main-logo.png') }}"
                class="h-14 mx-auto"
                alt="Logo SIG">
        </header>

        <main class="flex-1 relative bg-primary-gray flex items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 w-full h-full">
                <img
                    src="{{ asset('assets/background/bg-auth.png') }}"
                    class="w-full h-full object-cover"
                    alt="Background">
            </div>

            <!-- Content -->
            <div class="relative z-10 w-full px-4">
                {{ $slot }}
            </div>
        </main>

        <footer class="h-12 flex justify-center text-xs md:text-sm items-center rounded-t-full w-full color-gradient relative z-10">
            <p class="text-white">Copyright © 2025 SIG X Airlangga Psych Internship.</p>
        </footer>

        @fluxScripts
    </body>
</html>
