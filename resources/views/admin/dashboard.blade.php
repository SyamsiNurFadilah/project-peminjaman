@extends('layouts.app')

@section('title', 'Dashboard Petugas Admin - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Panel Administrator & Petugas
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">Pengelolaan Terpadu</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dashboard Manajemen Inventaris</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Pantau permintaan peminjaman mandiri mahasiswa, verifikasi transaksi, dan kelola aset inventaris kampus.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.borrowings') }}" 
                   class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm shadow-indigo-500/10 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Peminjaman
                </a>
                <a href="{{ route('admin.items') }}" 
                   class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Kelola Barang & QR
                </a>
                <a href="{{ route('catalog.index') }}" 
                   class="px-3.5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs transition hidden md:flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lihat Katalog
                </a>
            </div>
        </div>

        <!-- 4 Key Stat Cards (Clickable) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100">
            <!-- Menunggu Verifikasi -->
            <a href="{{ route('admin.borrowings', ['status' => 'pending']) }}" 
               class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/80 hover:bg-amber-50 hover:border-amber-300 transition group block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider group-hover:text-amber-900">Menunggu Verifikasi</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500 {{ $stats['pending_borrowings'] > 0 ? 'animate-ping' : '' }}"></span>
                </div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['pending_borrowings'] }}</div>
                <div class="text-[11px] text-amber-600/90 mt-0.5 flex items-center justify-between">
                    <span>Butuh persetujuan petugas</span>
                    <span class="font-bold">&rarr;</span>
                </div>
            </a>

            <!-- Sedang Dipinjam -->
            <a href="{{ route('admin.borrowings', ['status' => 'borrowed']) }}" 
               class="p-4 rounded-xl bg-blue-50/60 border border-blue-200/80 hover:bg-blue-50 hover:border-blue-300 transition group block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-blue-800 uppercase tracking-wider group-hover:text-blue-900">Sedang Dipinjam</span>
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['active_borrowings'] }}</div>
                <div class="text-[11px] text-blue-600/90 mt-0.5 flex items-center justify-between">
                    <span>Aset aktif di luar</span>
                    <span class="font-bold">&rarr;</span>
                </div>
            </a>

            <!-- Telah Selesai -->
            <a href="{{ route('admin.borrowings', ['status' => 'returned']) }}" 
               class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/80 hover:bg-emerald-50 hover:border-emerald-300 transition group block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider group-hover:text-emerald-900">Telah Dikembalikan</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $stats['returned_borrowings'] }}</div>
                <div class="text-[11px] text-emerald-600/90 mt-0.5 flex items-center justify-between">
                    <span>Peminjaman tuntas</span>
                    <span class="font-bold">&rarr;</span>
                </div>
            </a>

            <!-- Total Inventaris -->
            <a href="{{ route('admin.items') }}" 
               class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/70 hover:border-slate-300 transition group block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider group-hover:text-slate-900">Total Jenis Barang</span>
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                </div>
                <div class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_items'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center justify-between">
                    <span>{{ $stats['available_items'] }} jenis siap dipinjam</span>
                    <span class="font-bold">&rarr;</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Search Bar for Admin -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.borrowings') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <div>
                    <h3 class="text-xs font-bold text-slate-900 leading-tight">Pencarian Cepat Data & Transaksi</h3>
                    <p class="text-[11px] text-slate-500">Cari berdasarkan nama mahasiswa, NIM/NIP, atau kode QR barang</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-1 sm:max-w-md">
                <div class="relative flex-1">
                    <input type="text" 
                           name="q" 
                           placeholder="Ketik NIM, nama mahasiswa, atau kode..." 
                           class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-2 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shrink-0">
                    Cari Data
                </button>
            </div>
        </form>
    </div>

    <!-- 2 Column Section: Pending Verifications & Active Loans -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Pending Verifications (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900">Permintaan Menunggu Verifikasi</h2>
                        @if($stats['pending_borrowings'] > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                {{ $stats['pending_borrowings'] }} baru
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500">Mahasiswa yang mengajukan peminjaman via scan mandiri.</p>
                </div>
                <a href="{{ route('admin.borrowings', ['status' => 'pending']) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($pendingBorrowings->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingBorrowings as $pb)
                        <div class="p-4 rounded-xl border border-amber-200/80 bg-amber-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-slate-900 text-sm">{{ $pb->user->name }}</span>
                                    <span class="font-mono text-[11px] text-slate-500">NIM: {{ $pb->user->nim_nip }}</span>
                                </div>
                                <div class="text-xs text-slate-700">
                                    Meminjam: <strong class="text-slate-900">{{ $pb->item->name }}</strong> 
                                    <span class="font-mono text-blue-600 font-semibold text-[11px]">({{ $pb->item->item_code }})</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1">
                                    Durasi: {{ $pb->borrow_date->translatedFormat('d M') }} s.d {{ $pb->due_date->translatedFormat('d M Y') }}
                                    @if($pb->notes)
                                        • <em>"{{ Str::limit($pb->notes, 35) }}"</em>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('admin.borrowings.approve', $pb) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-xs flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.borrowings.reject', $pb) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-semibold text-xs transition">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-slate-400 text-xs">
                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tidak ada permintaan peminjaman baru yang menunggu verifikasi saat ini.
                </div>
            @endif
        </div>

        <!-- Right: Active Loans & Returns (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Peminjaman Berjalan</h2>
                    <p class="text-xs text-slate-500">Konfirmasi barang fisik yang dikembalikan.</p>
                </div>
                <a href="{{ route('admin.borrowings', ['status' => 'borrowed']) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Semua ({{ $stats['active_borrowings'] }})
                </a>
            </div>

            @if($activeBorrowings->count() > 0)
                <div class="space-y-3">
                    @foreach($activeBorrowings as $ab)
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/40 flex flex-col justify-between gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">{{ $ab->item->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $ab->user->name }} (NIM: {{ $ab->user->nim_nip }})</div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-500/20 whitespace-nowrap">
                                    Tenggat: {{ $ab->due_date->translatedFormat('d M') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                <span class="text-[11px] text-slate-400 font-mono">{{ $ab->item->item_code }}</span>
                                <form method="POST" action="{{ route('admin.borrowings.return', $ab) }}" onsubmit="return confirm('Konfirmasi bahwa barang ini telah dikembalikan dalam kondisi baik?')">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[11px] transition shadow-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Konfirmasi Kembali
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-slate-400 text-xs">
                    Tidak ada barang yang sedang dipinjam saat ini.
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
