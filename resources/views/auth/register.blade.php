@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="max-w-xl mx-auto py-6 sm:py-10">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Mahasiswa</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi formulir di bawah untuk membuat akun peminjaman inventaris kampus</p>
        </div>

        <!-- Form Register Mahasiswa -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-4" novalidate>
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Mahasiswa <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="misal: Samsi Nurfadilah"
                       class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIM -->
            <div>
                <label for="nim_nip" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nomor Induk Mahasiswa (NIM) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="nim_nip" 
                       name="nim_nip" 
                       value="{{ old('nim_nip') }}" 
                       required 
                       placeholder="misal: 220101001"
                       class="w-full rounded-xl border {{ $errors->has('nim_nip') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                <p class="text-[11px] text-slate-500 mt-1">NIM digunakan sebagai identitas saat login dan bukti transaksi peminjaman.</p>
                @error('nim_nip')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email Mahasiswa <span class="text-rose-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="misal: mahasiswa@kampus.ac.id"
                       class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kata Sandi & Konfirmasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ulangi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           placeholder="Ketik ulang kata sandi"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm shadow-blue-500/10 transition duration-150 ease-in-out cursor-pointer mt-2">
                Daftar Akun Mahasiswa
            </button>
        </form>

        <!-- Footer / Link to Login -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center text-sm text-slate-600">
            Sudah memiliki akun terdaftar? 
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                Masuk di sini
            </a>
        </div>

    </div>
</div>
@endsection
