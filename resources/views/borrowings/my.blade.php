@extends('layouts.app')

@section('title', 'Peminjaman Saya - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-600/10 mb-2">
                    Panel Mahasiswa
                </span>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Peminjaman Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Pantau status barang yang sedang Anda pinjam dan pastikan pengembalian sebelum tenggat waktu.
                </p>
            </div>

            <a href="{{ route('scan.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition shadow-sm shadow-blue-500/10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Pinjam Barang Baru
            </a>
        </div>

        <!-- Summary Counters -->
        <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-100">
            <div>
                <div class="text-xs text-slate-500 font-medium">Total Pengajuan</div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5">{{ $stats['total'] }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Sedang Dipinjam / Aktif</div>
                <div class="text-xl sm:text-2xl font-bold text-blue-600 mt-0.5">{{ $stats['active'] }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Telah Dikembalikan</div>
                <div class="text-xl sm:text-2xl font-bold text-emerald-600 mt-0.5">{{ $stats['returned'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('borrowings.my', ['status' => 'all']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentFilter === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Transaksi
        </a>
        <a href="{{ route('borrowings.my', ['status' => 'active']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentFilter === 'active' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Sedang Berjalan (Aktif)
        </a>
        <a href="{{ route('borrowings.my', ['status' => 'completed']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $currentFilter === 'completed' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai Dikembalikan
        </a>
    </div>

    <!-- Borrowings Table / List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if($borrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Barang Inventaris</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Tenggat Waktu</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @foreach($borrowings as $b)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Item name and code -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-snug">
                                        {{ $b->item->name ?? 'Barang Telah Dihapus' }}
                                    </div>
                                    <div class="font-mono text-[11px] text-blue-600 font-semibold mt-0.5">
                                        {{ $b->item->item_code ?? '-' }}
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $b->item->category ?? '-' }}
                                    </span>
                                </td>

                                <!-- Borrow Date -->
                                <td class="py-3.5 px-4 font-medium text-slate-700">
                                    {{ $b->borrow_date->translatedFormat('d F Y') }}
                                </td>

                                <!-- Due Date with countdown / overdue alert -->
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800">
                                        {{ $b->due_date->translatedFormat('d F Y') }}
                                    </div>
                                    @if(in_array($b->status, ['pending', 'borrowed']))
                                        @php
                                            $diff = now()->diffInDays($b->due_date, false);
                                        @endphp
                                        @if($diff < 0)
                                            <span class="inline-block text-[10px] font-bold text-rose-600">
                                                Lewat {{ abs($diff) }} hari!
                                            </span>
                                        @elseif($diff == 0)
                                            <span class="inline-block text-[10px] font-bold text-amber-600">
                                                Jatuh tempo hari ini
                                            </span>
                                        @else
                                            <span class="inline-block text-[10px] text-slate-400">
                                                Sisa {{ $diff }} hari lagi
                                            </span>
                                        @endif
                                    @elseif($b->status === 'returned' && $b->return_date)
                                        <span class="inline-block text-[10px] text-emerald-600">
                                            Kembali: {{ $b->return_date->translatedFormat('d M Y') }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4">
                                    @if($b->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($b->status === 'borrowed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            Sedang Dipinjam
                                        </span>
                                    @elseif($b->status === 'returned')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Telah Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- Notes -->
                                <td class="py-3.5 px-4 max-w-xs truncate text-slate-500">
                                    {{ $b->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
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
                <h3 class="text-sm font-bold text-slate-800">Belum Ada Riwayat Peminjaman</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Anda belum memiliki riwayat peminjaman barang. Silakan buka menu katalog atau scan kode QR untuk mulai meminjam.
                </p>
                <a href="{{ route('scan.index') }}" class="inline-block mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition">
                    Mulai Scan Sekarang
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
