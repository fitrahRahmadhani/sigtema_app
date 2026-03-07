<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>403 — Akses Ditolak | SIGTEMA</title>
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="flex min-h-screen flex-col bg-gray-950 font-sans text-slate-200">
        {{-- Header --}}
        <header class="flex flex-col items-start gap-2 border-b border-gray-800 px-10 py-4">
            <img src="{{ asset("assets/logo/logo-sigtema-red.png") }}" alt="Logo SIGTEMA" class="h-10" />
            <span
                class="rounded-sm border border-red-900 px-3 py-1 font-mono text-xs tracking-widest text-red-500 uppercase"
            >
                Security Incident
            </span>
        </header>

        {{-- Main --}}
        <main class="flex flex-1 items-center justify-center px-6 py-12">
            <div class="flex w-full max-w-3xl flex-col items-center">
                <img src="{{ asset("assets/error/403.png") }}" alt="403" class="mx-auto w-full py-10" />

                {{-- Title --}}
                <h1 class="mb-2 text-center text-3xl leading-snug font-bold text-slate-100 uppercase">
                    Anda
                    <span class="text-red-500">tidak memiliki izin</span>
                    untuk mengakses halaman ini
                </h1>

                {{-- Description --}}
                <p class="mb-6 max-w-xl text-center leading-relaxed text-slate-500">
                    Upaya akses ini telah dicatat secara otomatis oleh sistem keamanan SIGTEMA.
                    <br />
                    Seluruh aktivitas Anda tersimpan dalam log aktivitas.
                </p>

                {{-- Actions --}}
                <div class="mb-9">
                    <a
                        href="
                            @auth
                                @if (auth()->user()->hasRole("employee"))
                                    {{ route("my-profile") }}
                                @elseif (auth()->user()->hasRole("head-of-unit"))
                                    {{ route("career-path") }}
                                @else
                                    {{ route("dashboard") }}
                                @endif
                            @else
                                {{ route("login") }}
                            @endauth
                        "
                        class="border border-gray-800 px-5 py-3 font-mono text-xs tracking-widest text-gray-500 uppercase transition-colors duration-200 hover:border-gray-600 hover:text-slate-300"
                    >
                        ← Kembali ke Beranda
                    </a>
                </div>

                {{-- Surveillance Box --}}
                <div class="absolute top-4 right-0 w-1/2 bg-red-50/10 md:flex md:justify-end">
                    <div class="relative w-full overflow-hidden border border-l-4 border-red-500 border-l-red-600 p-4">
                        <p
                            class="mb-4 flex items-center gap-2 font-mono text-xs font-bold tracking-widest text-red-500 uppercase"
                        >
                            <span class="inline-block h-2 w-2 bg-red-600"></span>
                            Data Akses Terekam
                        </p>

                        <ul class="space-y-2.5 font-mono text-xs">
                            <li class="flex gap-3">
                                <span class="w-36 shrink-0 text-red-500">TIMESTAMP</span>
                                <span class="text-slate-400" id="rec-time">—</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="w-36 shrink-0 text-red-500">USER</span>
                                <span class="break-all text-slate-400">
                                    @auth
                                        {{ auth()->user()->name }} &lt;{{ auth()->user()->email }}&gt;
                                    @else
                                        Unauthenticated
                                    @endauth
                                </span>
                            </li>
                            <li class="flex gap-3">
                                <span class="w-36 shrink-0 text-red-500">ATTEMPTED URL</span>
                                <span class="break-all text-slate-400">{{ request()->fullUrl() }}</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="w-36 shrink-0 text-red-500">IP ADDRESS</span>
                                <span class="text-slate-400">{{ request()->ip() }}</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="w-36 shrink-0 text-red-500">USER AGENT</span>
                                <span class="break-all text-slate-400">
                                    {{ Str::limit(request()->userAgent(), 80) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer
            class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-800 px-10 py-4 font-mono text-xs text-gray-600"
        >
            <span>SIGTEMA Security System &copy; {{ date("Y") }}</span>
            <span>Semua hak dilindungi. Aktivitas tidak sah dapat dikenakan sanksi sesuai peraturan yang berlaku.</span>
        </footer>

        <script>
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            const ts =
                `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ` +
                `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
            const incidentId =
                'INC-' +
                now.getFullYear() +
                pad(now.getMonth() + 1) +
                pad(now.getDate()) +
                '-' +
                Math.random().toString(36).slice(2, 8).toUpperCase();

            document.getElementById('rec-time').textContent = ts;
            document.getElementById('incident-id').textContent = incidentId;
            document.getElementById('footer-incident').textContent = 'Incident Reference: ' + incidentId;
        </script>
    </body>
</html>
