<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ request()->routeIs('login') ? 'SIGTEMA — SIG Talent Empowerment & Mobility Assistance' : ($title ?? config('app.name')) . ' - SIGTEMA' }}
    
</title>

<link rel="icon" href="/favicon.ico" sizes="any" />
<link rel="icon" href="/favicon.svg" type="image/svg+xml" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" href="/favicon.png" />

<meta
    name="description"
    content="SIGTEMA adalah Sistem Informasi Manajemen Talenta untuk pengelolaan SDM secara efisien dan terintegrasi."
/>
<meta
    name="keywords"
    content="SIGTEMA, sigtema, SIG, sig, Semen Indonesia, semen indonesia, Semen Indonesia Group, semen indonesia group, Semen Tuban, semen tuban, sig talent empowerment, sig talent empowerment & mobility assistance"
/>
<meta name="robots" content="{{ request()->routeIs('login') ? 'noindex, nofollow' : 'index, follow' }}" />
<meta name="author" content="SIGTEMA" />
<link rel="canonical" href="{{ url()->current() }}" />

<meta name="robots" content="{{ request()->routeIs('login') ? 'noindex, nofollow' : 'index, follow' }}" />

{{-- Preconnect untuk koneksi lebih cepat --}}
<link rel="preconnect" href="https://fonts.bunny.net" />

{{-- Stylesheet untuk load font --}}
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Aktifkan @fluxAppearance jika ingin menggunakan dark mode --}}
{{-- @fluxAppearance --}}
