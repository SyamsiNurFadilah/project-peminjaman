@extends('layouts.app')

@section('title', $item->name . ' - Detail Inventaris')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            
            <!-- Left: QR Code Display -->
            <div class="text-center bg-slate-50 rounded-2xl border border-slate-200/80 p-6 flex flex-col items-center">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">QR Code Inventaris</span>
                <div class="p-3 bg-white border border-slate-200 rounded-xl shadow-xs inline-block">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($item->item_code) }}" 
                         alt="QR Code {{ $item->item_code }}" 
                         class="w-48 h-48 rounded-lg object-contain">
                </div>
                <div class="mt-3 font-mono text-sm font-bold text-slate-800 tracking-wider">
                    {{ $item->item_code }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Pindai dengan kamera smartphone / scanner kampus</p>
            </div>

            <!-- Right: Details & Status -->
            <div class="md:col-span-2 space-y-5">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                            {{ $item->category }}
                        </span>

                        @if($item->status === 'available')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                Tersedia ({{ $item->available_stock }} unit)
                            </span>
                        @elseif($item->status === 'empty')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                                Stok Habis
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                Sedang Dalam Pemeliharaan
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-snug">
                        {{ $item->name }}
                    </h1>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Aset</div>
                        <div class="text-lg font-bold text-slate-900 mt-0.5">{{ $item->total_stock }} Unit</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Unit Tersedia</div>
                        <div class="text-lg font-bold text-emerald-600 mt-0.5">{{ $item->available_stock }} Unit</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</div>
                        <div class="text-lg font-bold text-blue-600 mt-0.5">{{ max(0, $item->total_stock - $item->available_stock) }} Unit</div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.items') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Kelola Barang di Panel Admin
                            </a>
                            <a href="{{ route('admin.items.print_qr', $item) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.37-2.16-.37-3.294 0-4.662 3.806-8.441 8.5-8.441s8.5 3.779 8.5 8.441c0 1.133-.13 2.238-.37 3.294M6.72 13.829a8.441 8.441 0 00-4.62 7.421h19.8c0-2.883-1.442-5.42-3.66-6.921M6.72 13.829A8.442 8.442 0 0112 11.25c2.072 0 3.966.75 5.42 2M6.75 21v-3.75h10.5V21" />
                                </svg>
                                Cetak Label QR
                            </a>
                        </div>
                    @else
                        @if($item->isAvailable())
                            <a href="{{ route('scan.index', ['code' => $item->item_code]) }}" 
                               class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm shadow-blue-500/10 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                </svg>
                                Pinjam Barang Ini Sekarang (Scan Mandiri)
                            </a>
                        @else
                            <button disabled class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 text-slate-400 font-semibold text-sm cursor-not-allowed">
                                Barang Tidak Dapat Dipinjam Saat Ini
                            </button>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Recent Loan History Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 mb-4">Riwayat Peminjaman Terakhir</h2>
        @if($item->borrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="pb-2.5">Peminjam</th>
                            <th class="pb-2.5">Tgl Pinjam</th>
                            <th class="pb-2.5">Tenggat Kembali</th>
                            <th class="pb-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @foreach($item->borrowings as $b)
                            <tr>
                                <td class="py-3 font-medium text-slate-800">{{ $b->user->name ?? '-' }}</td>
                                <td class="py-3">{{ $b->borrow_date->format('d M Y') }}</td>
                                <td class="py-3">{{ $b->due_date->format('d M Y') }}</td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                        {{ $b->status === 'borrowed' ? 'bg-blue-50 text-blue-700' : ($b->status === 'returned' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 italic">Belum ada catatan riwayat peminjaman untuk barang ini.</p>
        @endif
    </div>

</div>
@endsection
