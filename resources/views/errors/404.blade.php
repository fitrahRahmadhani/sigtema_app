<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>404 — Halaman Tidak Ditemukan | SIGTEMA</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-gray-950 font-sans text-slate-200">
        {{-- Header --}}
        <header class="flex items-center justify-between border-b border-gray-800 px-10 py-4">
            <img src="{{ asset('assets/logo/logo-sigtema-red.png') }}" alt="Logo SIGTEMA" class="h-10" />
        </header>

        {{-- Main --}}
        <main class="flex flex-1 flex-col items-center justify-center px-6 py-12 text-center">
            {{-- Ilustrasi --}}
            <img src="{{ asset('assets/error/404.png') }}" alt="404" class="mx-auto mb-8 w-72 sm:w-80 md:w-96" />

            {{-- Title --}}
            <h1 class="mb-3 text-3xl font-bold text-slate-100 uppercase">
                Halaman
                <span class="text-red-500">Tidak Ditemukan</span>
            </h1>

            {{-- Subtitle --}}
            <p class="mb-8 max-w-md leading-relaxed text-slate-500">
                Halaman yang Anda cari tidak ada atau telah dipindahkan. Periksa kembali URL yang Anda masukkan.
            </p>
        </main>

        {{-- Footer --}}
        <footer
            class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-800 px-10 py-4 font-mono text-xs text-gray-600"
        >
            <span>SIGTEMA Security System &copy; {{ date('Y') }}</span>
            <span>Semua hak dilindungi. Aktivitas tidak sah dapat dikenakan sanksi sesuai peraturan yang berlaku.</span>
        </footer>
    </body>
</html>
