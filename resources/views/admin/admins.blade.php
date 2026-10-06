@extends('layouts.app')

@section('title', 'Kelola Petugas / Admin - Panel Administrator')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7 0 3.75 3.75 0 017 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Hak Akses Sistem
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">Petugas & Administrator</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Kelola Petugas & Administrator</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Daftarkan akun petugas baru untuk mengelola inventaris, verifikasi peminjaman, dan cetak kode QR.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Dashboard Admin
                </a>
                <button type="button" 
                        onclick="document.getElementById('register-admin-card').scrollIntoView({behavior: 'smooth'})"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm shadow-indigo-500/10 flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                    </svg>
                    + Daftarkan Petugas Baru
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-6 pt-6 border-t border-slate-100">
            <div class="p-3.5 rounded-xl bg-indigo-50/60 border border-indigo-100">
                <div class="text-[11px] font-semibold text-indigo-700 uppercase tracking-wider">Total Petugas / Admin</div>
                <div class="text-2xl font-bold text-indigo-950 mt-1">{{ $totalAdmins }}</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Akun Mahasiswa</div>
                <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalStudents }}</div>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 col-span-2 sm:col-span-1">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Akun Aktif Anda</div>
                <div class="text-sm font-bold text-slate-800 mt-1 truncate">{{ auth()->user()->name }}</div>
                <div class="text-[11px] text-slate-500">NIP: {{ auth()->user()->nim_nip }}</div>
            </div>
        </div>
    </div>

    <!-- Grid: Register Form + List Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Form Pendaftaran Petugas Baru -->
        <div id="register-admin-card" class="lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs h-fit">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftarkan Petugas Baru</h2>
                    <p class="text-xs text-slate-500">Akun akan langsung berstatus Administrator</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-4" novalidate>
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Lengkap Petugas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="misal: Bpk. Ahmad Yani, M.T."
                           class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIP / Kode Petugas -->
                <div>
                    <label for="nim_nip" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        NIP / Kode Petugas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nim_nip" 
                           name="nim_nip" 
                           value="{{ old('nim_nip') }}" 
                           required 
                           placeholder="misal: 198804122015041001"
                           class="w-full rounded-xl border {{ $errors->has('nim_nip') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Dapat berupa NIP kampus atau nomor kepegawaian inventaris.</p>
                    @error('nim_nip')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="misal: ahmad@kampus.ac.id"
                           class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Kata Sandi Awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           placeholder="Ulangi kata sandi"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                </div>

                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm shadow-indigo-500/10 transition cursor-pointer mt-1 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Daftarkan Petugas Sekarang
                </button>
            </form>
        </div>

        <!-- Right Column: Daftar Petugas Terdaftar -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Search Bar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <form method="GET" action="{{ route('admin.admins') }}" class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text" 
                               name="q" 
                               value="{{ $searchQuery }}" 
                               placeholder="Cari berdasarkan nama, NIP, atau email petugas..."
                               class="w-full rounded-xl border border-slate-200 pl-9 pr-3.5 py-2 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition">
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition cursor-pointer">
                        Cari
                    </button>
                    @if($searchQuery)
                        <a href="{{ route('admin.admins') }}" 
                           class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Admins -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Daftar Administrator Terdaftar</h3>
                    <span class="text-xs text-slate-500">{{ $admins->total() }} akun terdaftar</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th scope="col" class="py-3 px-4">Nama Petugas</th>
                                <th scope="col" class="py-3 px-4">NIP / Identitas</th>
                                <th scope="col" class="py-3 px-4">Email</th>
                                <th scope="col" class="py-3 px-4">Terdaftar</th>
                                <th scope="col" class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($admins as $admin)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span>{{ $admin->name }}</span>
                                                @if($admin->id === auth()->id())
                                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20">
                                                        Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-700">
                                        {{ $admin->nim_nip }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500">
                                        {{ $admin->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        @if($admin->id === auth()->id())
                                            <span class="text-[11px] text-slate-400 italic">Akun aktif</span>
                                        @else
                                            <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akses administrator untuk {{ $admin->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 rounded-lg border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition cursor-pointer"
                                                        title="Hapus Petugas">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        Tidak ada data administrator yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($admins->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
