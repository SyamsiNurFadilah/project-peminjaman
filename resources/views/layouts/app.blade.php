<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Peminjaman Mandiri Kampus')</title>

    <!-- Google Fonts / Instrument Sans via Vite -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : route('catalog.index') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 group-hover:bg-blue-700 shadow-blue-500/20 text-white flex items-center justify-center font-bold text-lg shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h3v3h-3v-3zM13.5 19.5h3v1.5h-3v-1.5zM19.5 13.5h1.5v3h-1.5v-3zM19.5 19.5h1.5v1.5h-1.5v-1.5zM16.5 16.5h3v3h-3v-3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 tracking-tight text-base sm:text-lg block leading-tight">PinjamKampus</span>
                            <span class="text-xs text-slate-500 font-medium hidden sm:block">
                                {{ auth()->check() && auth()->user()->isAdmin() ? 'Panel Administrator & Petugas' : 'Sistem Peminjaman Inventaris Mandiri' }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.borrowings') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('admin.borrowings*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Peminjaman
                            </a>
                            <a href="{{ route('admin.items') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('admin.items*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Barang
                            </a>
                            <a href="{{ route('admin.admins') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('admin.admins*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Petugas
                            </a>
                            <a href="{{ route('catalog.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Katalog
                            </a>
                        @else
                            <a href="{{ route('catalog.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Katalog Inventaris
                            </a>
                            <a href="{{ route('scan.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5 whitespace-nowrap {{ request()->routeIs('scan.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                </svg>
                                Scan Mandiri (QR)
                            </a>
                            <a href="{{ route('borrowings.my') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('borrowings.my') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                Peminjaman Saya
                            </a>
                        @endif
                    @else
                        <a href="{{ route('catalog.index') }}" 
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Katalog Inventaris
                        </a>
                        <a href="{{ route('scan.index') }}" 
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5 whitespace-nowrap {{ request()->routeIs('scan.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                            Scan Mandiri (QR)
                        </a>
                    @endauth
                </nav>

                <!-- User Area -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-semibold text-slate-800 leading-snug">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 flex items-center justify-end gap-1.5">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium {{ Auth::user()->isAdmin() ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20' : 'bg-slate-100 text-slate-600' }}">
                                        {{ Auth::user()->isAdmin() ? 'Petugas Admin' : 'Mahasiswa' }}
                                    </span>
                                    <span>NIM/NIP: {{ Auth::user()->nim_nip }}</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        title="Keluar dari akun"
                                        class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition cursor-pointer"
                                        onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition whitespace-nowrap {{ request()->routeIs('login') ? 'bg-slate-100 text-slate-900' : '' }}">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-sm shadow-blue-500/10 transition whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                                </svg>
                                <span>Daftar</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Sub-bar -->
        <div class="md:hidden border-t border-slate-100 bg-white px-4 py-2 flex items-center justify-around text-xs font-medium text-slate-600">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-bold bg-indigo-50' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.borrowings') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('admin.borrowings*') ? 'text-indigo-600 font-bold bg-indigo-50' : '' }}">
                        Peminjaman
                    </a>
                    <a href="{{ route('admin.items') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('admin.items*') ? 'text-indigo-600 font-bold bg-indigo-50' : '' }}">
                        Barang
                    </a>
                    <a href="{{ route('admin.admins') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('admin.admins*') ? 'text-indigo-600 font-bold bg-indigo-50' : '' }}">
                        Petugas
                    </a>
                    <a href="{{ route('catalog.index') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'text-indigo-600 font-bold bg-indigo-50' : '' }}">
                        Katalog
                    </a>
                @else
                    <a href="{{ route('catalog.index') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'text-blue-600 font-bold bg-blue-50' : '' }}">
                        Katalog
                    </a>
                    <a href="{{ route('scan.index') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('scan.*') ? 'text-blue-600 font-bold bg-blue-50' : '' }}">
                        Scan QR
                    </a>
                    <a href="{{ route('borrowings.my') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('borrowings.my') ? 'text-blue-600 font-bold bg-blue-50' : '' }}">
                        Riwayat
                    </a>
                @endif
            @else
                <a href="{{ route('catalog.index') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('catalog.*') ? 'text-blue-600 font-bold bg-blue-50' : '' }}">
                    Katalog
                </a>
                <a href="{{ route('scan.index') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('scan.*') ? 'text-blue-600 font-bold bg-blue-50' : '' }}">
                    Scan QR
                </a>
                <a href="{{ route('login') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('login') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-600' }}">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="py-1 px-2.5 rounded whitespace-nowrap {{ request()->routeIs('register') ? 'text-blue-600 font-bold bg-blue-50' : 'text-blue-600 font-semibold' }}">
                    Daftar
                </a>
            @endauth
        </div>
    </header>

    @auth
        @if(Auth::user()->isAdmin() && !request()->is('admin*'))
            <div class="bg-indigo-900 text-indigo-100 text-xs py-2 px-4 border-b border-indigo-800 shadow-xs">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-indigo-700 font-bold text-[10px] uppercase tracking-wider text-white">Mode Pratinjau Petugas</span>
                        <span>Anda sedang menjelajahi halaman sebagai <strong>Administrator Petugas ({{ Auth::user()->name }})</strong>.</span>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white text-indigo-950 font-bold text-xs hover:bg-indigo-50 transition shadow-xs self-start sm:self-auto">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Kembali ke Dashboard Admin
                    </a>
                </div>
            </div>
        @endif
    @endauth

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Terjadi beberapa kesalahan:
                </div>
                <ul class="list-disc list-inside text-sm space-y-0.5 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>&copy; {{ date('Y') }} Sistem Peminjaman Inventaris Mandiri Kampus. Dibuat dengan Laravel, Tailwind CSS, dan Vite.</p>
            <div class="flex items-center gap-4 text-slate-400">
                <span>Lab</span>
                <span>•</span>
                <span>Perpustakaan</span>
                <span>•</span>
                <span>Alat Himpunan</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
