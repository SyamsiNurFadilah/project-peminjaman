@extends('layouts.app')

@section('title', 'Verifikasi & Kelola Peminjaman - Admin Inventaris')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Petugas Inventaris
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">Manajemen Transaksi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Verifikasi & Riwayat Peminjaman</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Verifikasi permohonan pinjam mandiri dari mahasiswa, konfirmasi pengembalian barang fisik, dan pantau status keterlambatan.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Dashboard Admin
                </a>
                <a href="{{ route('admin.items') }}" 
                   class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm shadow-indigo-500/10 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Kelola Barang & QR
                </a>
            </div>
        </div>

        <!-- Summary Counters Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mt-6 pt-6 border-t border-slate-100">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Semua Data</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $counts['all'] }}</div>
            </div>
            <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200/60">
                <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider flex items-center justify-between">
                    <span>Menunggu</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500 {{ $counts['pending'] > 0 ? 'animate-ping' : '' }}"></span>
                </div>
                <div class="text-xl font-bold text-amber-700 mt-0.5">{{ $counts['pending'] }}</div>
            </div>
            <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-200/60">
                <div class="text-[11px] font-semibold text-blue-800 uppercase tracking-wider">Sedang Dipinjam</div>
                <div class="text-xl font-bold text-blue-700 mt-0.5">{{ $counts['borrowed'] }}</div>
            </div>
            <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200/60">
                <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Dikembalikan</div>
                <div class="text-xl font-bold text-emerald-700 mt-0.5">{{ $counts['returned'] }}</div>
            </div>
            <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-200/60 col-span-2 sm:col-span-1">
                <div class="text-[11px] font-semibold text-rose-800 uppercase tracking-wider">Ditolak</div>
                <div class="text-xl font-bold text-rose-700 mt-0.5">{{ $counts['rejected'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.borrowings') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Status Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <a href="{{ route('admin.borrowings', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentStatus === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin.borrowings', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition flex items-center gap-1.5 {{ $currentStatus === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    @if($counts['pending'] > 0)
                        <span class="w-1.5 h-1.5 rounded-full {{ $currentStatus === 'pending' ? 'bg-white' : 'bg-amber-600' }} animate-pulse"></span>
                    @endif
                    Menunggu ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('admin.borrowings', array_merge(request()->except(['status', 'page']), ['status' => 'borrowed'])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentStatus === 'borrowed' ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Dipinjam ({{ $counts['borrowed'] }})
                </a>
                <a href="{{ route('admin.borrowings', array_merge(request()->except(['status', 'page']), ['status' => 'returned'])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentStatus === 'returned' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Selesai ({{ $counts['returned'] }})
                </a>
                <a href="{{ route('admin.borrowings', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Ditolak ({{ $counts['rejected'] }})
                </a>
            </div>

            <!-- Search Field -->
            <div class="flex items-center gap-2">
                @if($currentStatus !== 'all')
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif

                <div class="relative flex-1 sm:w-72">
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari mahasiswa, NIM, atau barang..." 
                           class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-1.5 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <button type="submit" class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Cari
                </button>

                @if(!empty($searchQuery) || $currentStatus !== 'all')
                    <a href="{{ route('admin.borrowings') }}" title="Reset Filter" class="p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition text-xs font-medium">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Borrowings Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if($borrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Peminjam (Mahasiswa)</th>
                            <th class="py-3.5 px-4">Barang Inventaris</th>
                            <th class="py-3.5 px-4">Jadwal Pinjam</th>
                            <th class="py-3.5 px-4">Status & Tenggat</th>
                            <th class="py-3.5 px-4">Catatan</th>
                            <th class="py-3.5 px-4 text-right">Tindakan Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @foreach($borrowings as $b)
                            <tr class="hover:bg-slate-50/60 transition">
                                <!-- User Info -->
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-snug">
                                        {{ $b->user->name ?? 'Pengguna Dihapus' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                        NIM: {{ $b->user->nim_nip ?? '-' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[180px]">
                                        {{ $b->user->email ?? '-' }}
                                    </div>
                                </td>

                                <!-- Item Info -->
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 leading-snug">
                                        {{ $b->item->name ?? 'Barang Dihapus' }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="font-mono text-blue-600 font-semibold text-[11px]">
                                            {{ $b->item->item_code ?? '-' }}
                                        </span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-[10px] text-slate-500 px-1.5 py-0.5 rounded bg-slate-100">
                                            {{ $b->item->category ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Schedule -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-700">
                                        Pinjam: {{ $b->borrow_date->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">
                                        Tenggat: <strong class="text-slate-700">{{ $b->due_date->translatedFormat('d M Y') }}</strong>
                                    </div>
                                    @if($b->status === 'returned' && $b->return_date)
                                        <div class="text-emerald-600 text-[11px] font-semibold mt-0.5">
                                            Kembali: {{ $b->return_date->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status Badge & Countdown -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($b->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($b->status === 'borrowed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            Sedang Dipinjam
                                        </span>
                                        @php
                                            $diff = now()->diffInDays($b->due_date, false);
                                        @endphp
                                        <div class="mt-1">
                                            @if($diff < 0)
                                                <span class="inline-block text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                                                    Terlambat {{ abs($diff) }} hari!
                                                </span>
                                            @elseif($diff == 0)
                                                <span class="inline-block text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">
                                                    Jatuh tempo hari ini
                                                </span>
                                            @else
                                                <span class="inline-block text-[10px] text-slate-500">
                                                    Sisa {{ $diff }} hari
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($b->status === 'returned')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Telah Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Permohonan Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- Notes -->
                                <td class="py-4 px-4 max-w-xs">
                                    @if($b->notes)
                                        <div class="text-xs text-slate-700 line-clamp-2" title="{{ $b->notes }}">
                                            {{ $b->notes }}
                                        </div>
                                    @else
                                        <span class="text-slate-300 italic text-[11px]">Tanpa catatan</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    @if($b->status === 'pending')
                                        <div class="flex items-center justify-end gap-1.5">
                                            <form method="POST" action="{{ route('admin.borrowings.approve', $b) }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-xs flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.borrowings.reject', $b) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak permohonan peminjaman ini?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-semibold text-xs transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($b->status === 'borrowed')
                                        <form method="POST" action="{{ route('admin.borrowings.return', $b) }}" onsubmit="return confirm('Konfirmasi bahwa barang ini telah diterima kembali dari mahasiswa?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition shadow-xs inline-flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Konfirmasi Pengembalian
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px] font-medium">Transaksi Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="p-4 border-t border-slate-100">
                {{ $borrowings->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak ada riwayat peminjaman</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Tidak ditemukan data peminjaman yang sesuai dengan filter atau kata kunci pencarian yang Anda masukkan.
                </p>
                <a href="{{ route('admin.borrowings') }}" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:underline">
                    Reset Semua Filter
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
