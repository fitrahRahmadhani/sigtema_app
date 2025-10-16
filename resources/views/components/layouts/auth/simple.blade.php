<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    {{-- class="dark" --}}
    {{-- Aktifkan class="dark" jika ingin menggunakan dark mode --}}
>
    <head>
        @include('partials.head')
    </head>
    <body class="bg-primary-gray flex min-h-screen flex-col antialiased">
        <header class="bg-secondary-gray relative z-10 flex h-28 w-full items-center justify-center">
            <img src="{{ asset('assets/logo/sig-main-logo.png') }}" class="mx-auto h-14" alt="Logo SIG" />
        </header>

        <main class="bg-primary-gray relative flex flex-1 items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 h-full w-full">
                <img
                    src="{{ asset('assets/background/bg-auth.png') }}"
                    class="h-full w-full object-cover"
                    alt="Background"
                />
            </div>

            <!-- Content -->
            <div class="relative z-10 w-full px-4">
                {{ $slot }}
            </div>
        </main>

        <footer
            class="color-gradient relative z-10 flex h-12 w-full items-center justify-center rounded-t-full text-xs md:text-sm"
        >
            <p class="text-white">Copyright &copy; {{ now()->year }} Unit of Opr HC, GA & Asset Management</p>
        </footer>

        @fluxScripts
    </body>
</html>
