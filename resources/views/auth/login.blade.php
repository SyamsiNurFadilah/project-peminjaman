@extends('layouts.app')

@section('title', 'Masuk - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="max-w-md mx-auto py-8 sm:py-12">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7 0 3.75 3.75 0 017 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Gunakan Email atau NIM / NIP yang telah terdaftar</p>
        </div>

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="login" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email atau NIM / NIP <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="login" 
                           name="login" 
                           value="{{ old('login') }}" 
                           required 
                           autofocus
                           placeholder="misal: nama@kampus.ac.id atau 220101001"
                           class="w-full rounded-xl border {{ $errors->has('login') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                </div>
                @error('login')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="Masukkan kata sandi Anda"
                       class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Ingat Saya di Perangkat Ini</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm shadow-blue-500/10 transition duration-150 ease-in-out cursor-pointer mt-2">
                Masuk ke Akun
            </button>
        </form>

        <!-- Footer / Link to Register -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center text-sm text-slate-600">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                Daftar Sekarang
            </a>
        </div>

    </div>
</div>
@endsection
