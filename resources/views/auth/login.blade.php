@extends('layouts.app')

@section('title', 'Masuk - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7 0 3.75 3.75 0 017 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Silakan gunakan Email atau NIM / NIP kampus Anda</p>
        </div>

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
            @csrf

            <div>
                <label for="login" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email atau NIM / NIP
                </label>
                <div class="relative">
                    <input type="text" 
                           id="login" 
                           name="login" 
                           value="{{ old('login') }}" 
                           required 
                           autofocus
                           placeholder="misal: samsi@mahasiswa.ac.id atau 220101001"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Ingat Saya</span>
                </label>
                <span class="text-slate-400">Password default seeder: <code class="text-slate-700 font-mono">password</code></span>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm shadow-blue-500/10 transition duration-150 ease-in-out cursor-pointer">
                Masuk Sekarang
            </button>
        </form>

        <!-- Quick Demo Switcher -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-center mb-3">
                Pilih Akun Demo (Klik untuk Otomatis Isi)
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <button type="button" 
                        onclick="fillDemo('samsi@mahasiswa.ac.id', 'password')"
                        class="p-3 rounded-xl border border-slate-200 text-left hover:border-blue-400 hover:bg-blue-50/50 transition group cursor-pointer">
                    <div class="flex items-center justify-between mb-0.5">
                        <span class="text-xs font-bold text-slate-800 group-hover:text-blue-700">Mahasiswa</span>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">&rarr; Katalog</span>
                    </div>
                    <div class="text-[11px] text-slate-500 truncate">samsi@mahasiswa.ac.id</div>
                </button>
                <button type="button" 
                        onclick="fillDemo('admin@kampus.ac.id', 'password')"
                        class="p-3 rounded-xl border border-slate-200 text-left hover:border-indigo-400 hover:bg-indigo-50/50 transition group cursor-pointer">
                    <div class="flex items-center justify-between mb-0.5">
                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Petugas Admin</span>
                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">&rarr; Panel Admin</span>
                    </div>
                    <div class="text-[11px] text-slate-500 truncate">admin@kampus.ac.id</div>
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function fillDemo(login, password) {
        document.getElementById('login').value = login;
        document.getElementById('password').value = password;
    }
</script>
@endpush
@endsection
