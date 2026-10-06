@extends('layouts.app')

@section('title', 'Kelola Barang & Generator QR - Admin Inventaris')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        </svg>
                        Manajemen Aset Kampus
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">Inventaris & Kode QR</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Kelola Inventaris & Generator QR Code</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Tambahkan barang baru dengan kode QR otomatis, sesuaikan stok fisik, dan cetak label stiker QR untuk ditempel pada aset kampus.
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
                        onclick="openCreateModal()" 
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm shadow-indigo-500/10 flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    + Tambah Barang Baru
                </button>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-100">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Jenis Barang</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $counts['all'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60">
                <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Siap Dipinjam</div>
                <div class="text-xl font-bold text-emerald-700 mt-0.5">{{ $counts['available'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
            <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200/60">
                <div class="text-[11px] font-semibold text-rose-800 uppercase tracking-wider">Stok Habis (0)</div>
                <div class="text-xl font-bold text-rose-700 mt-0.5">{{ $counts['empty'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/60">
                <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider">Dalam Perbaikan</div>
                <div class="text-xl font-bold text-amber-700 mt-0.5">{{ $counts['maintenance'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.items') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Category Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <a href="{{ route('admin.items', array_merge(request()->except(['category', 'page']), ['category' => ''])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('admin.items', array_merge(request()->except(['category', 'page']), ['category' => $cat])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $selectedCategory === $cat ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Search and Status Filter -->
            <div class="flex items-center gap-2">
                @if(!empty($selectedCategory))
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-300 text-xs font-medium text-slate-700 py-2 px-3 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <option value="">Semua Status</option>
                    <option value="available" {{ $selectedStatus === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="empty" {{ $selectedStatus === 'empty' ? 'selected' : '' }}>Habis</option>
                    <option value="maintenance" {{ $selectedStatus === 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                </select>

                <div class="relative flex-1 sm:w-64">
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari nama atau kode QR..." 
                           class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-1.5 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <button type="submit" class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Cari
                </button>

                @if(!empty($searchQuery) || !empty($selectedCategory) || !empty($selectedStatus))
                    <a href="{{ route('admin.items') }}" title="Reset Filter" class="p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition text-xs font-medium">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Inventory Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        @if($items->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Kode QR & Aset</th>
                            <th class="py-3.5 px-4">Nama Barang</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Ketersediaan Stok</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @foreach($items as $item)
                            <tr class="hover:bg-slate-50/60 transition">
                                <!-- QR Thumbnail & Code -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" 
                                                onclick="openQrModal({{ $item->id }}, '{{ $item->item_code }}', '{{ addslashes($item->name) }}', '{{ $item->category }}')" 
                                                title="Klik untuk pratinjau QR Code"
                                                class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 hover:border-indigo-400 p-1 flex items-center justify-center transition group">
                                            <svg class="w-5 h-5 text-slate-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h3v3h-3v-3zM13.5 19.5h3v1.5h-3v-1.5zM19.5 13.5h1.5v3h-1.5v-3zM19.5 19.5h1.5v1.5h-1.5v-1.5zM16.5 16.5h3v3h-3v-3z" />
                                            </svg>
                                        </button>
                                        <div>
                                            <div class="font-mono text-blue-600 font-bold text-xs">{{ $item->item_code }}</div>
                                            <button type="button" 
                                                    onclick="openQrModal({{ $item->id }}, '{{ $item->item_code }}', '{{ addslashes($item->name) }}', '{{ $item->category }}')" 
                                                    class="text-[10px] text-slate-400 hover:text-indigo-600 flex items-center gap-0.5">
                                                <span>Lihat QR</span>
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Item Name -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-snug">
                                        {{ $item->name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        ID #{{ $item->id }} • Ditambahkan {{ $item->created_at->translatedFormat('d M Y') }}
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $item->category }}
                                    </span>
                                </td>

                                <!-- Stock Availability -->
                                <td class="py-3.5 px-4 whitespace-nowrap min-w-[160px]">
                                    <div class="flex items-center justify-between text-xs text-slate-700 font-semibold mb-1">
                                        <span>{{ $item->available_stock }} <span class="font-normal text-slate-400">tersedia</span></span>
                                        <span class="text-slate-400 font-normal">/ {{ $item->total_stock }} unit</span>
                                    </div>
                                    @php
                                        $pct = $item->total_stock > 0 ? ($item->available_stock / $item->total_stock) * 100 : 0;
                                    @endphp
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $item->available_stock > 0 ? 'bg-blue-600' : 'bg-slate-300' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($item->status === 'available')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Tersedia
                                        </span>
                                    @elseif($item->status === 'empty')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Stok Habis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            Perbaikan
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Print QR Sticker Link -->
                                        <a href="{{ route('admin.items.print_qr', $item) }}" 
                                           title="Cetak Stiker Label QR"
                                           class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 font-semibold text-xs transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.37-2.16-.37-3.294 0-4.662 3.806-8.441 8.5-8.441s8.5 3.779 8.5 8.441c0 1.133-.13 2.238-.37 3.294M6.72 13.829a8.441 8.441 0 00-4.62 7.421h19.8c0-2.883-1.442-5.42-3.66-6.921M6.72 13.829A8.442 8.442 0 0112 11.25c2.072 0 3.966.75 5.42 2M6.75 21v-3.75h10.5V21" />
                                            </svg>
                                            Cetak QR
                                        </a>

                                        <!-- Edit Item Button -->
                                        <button type="button" 
                                                onclick='openEditModal(@json($item))'
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                            Edit
                                        </button>

                                        <!-- Delete Item Button -->
                                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang \'{{ addslashes($item->name) }}\' dari inventaris?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" 
                                                    title="Hapus Barang">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $items->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak ada barang inventaris</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Tidak ditemukan data barang yang sesuai dengan filter atau kata kunci pencarian Anda.
                </p>
                <button type="button" onclick="openCreateModal()" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:underline">
                    + Tambah Barang Sekarang
                </button>
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Tambah Barang Baru -->
<div id="createItemModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-xl relative animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tambah Barang Inventaris Baru</h3>
                <p class="text-xs text-slate-500">Sistem otomatis men-generate kode QR unik jika dikosongkan.</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.items.store') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <label for="create_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Barang <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="create_name" 
                       name="name" 
                       required 
                       placeholder="misal: Proyektor Epson EB-X500" 
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="create_category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="create_category" name="category" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                        <option value="Lab">Lab</option>
                        <option value="Perpustakaan">Perpustakaan</option>
                        <option value="Alat Himpunan">Alat Himpunan</option>
                    </select>
                </div>

                <div>
                    <label for="create_total_stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Jumlah Stok <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           id="create_total_stock" 
                           name="total_stock" 
                           min="1" 
                           value="1" 
                           required 
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                </div>
            </div>

            <div>
                <label for="create_item_code" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Kode Barang / QR (Opsional)
                </label>
                <input type="text" 
                       id="create_item_code" 
                       name="item_code" 
                       placeholder="Kosongkan untuk auto-generate (contoh: INV-LAB-XXXX)" 
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 placeholder:text-slate-400 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
            </div>

            <div>
                <label for="create_status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Status Awal <span class="text-rose-500">*</span>
                </label>
                <select id="create_status" name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <option value="available">Tersedia (Siap Dipinjam)</option>
                    <option value="empty">Stok Habis</option>
                    <option value="maintenance">Dalam Perbaikan</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm">
                    Simpan Barang & Buat QR
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Edit Barang -->
<div id="editItemModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-xl relative animate-in fade-in zoom-in-95 duration-150">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Data Barang</h3>
                <p id="edit_code_label" class="text-xs font-mono text-indigo-600 font-semibold"></p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Barang <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="edit_name" 
                       name="name" 
                       required 
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
            </div>

            <div>
                <label for="edit_category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Kategori <span class="text-rose-500">*</span>
                </label>
                <select id="edit_category" name="category" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <option value="Lab">Lab</option>
                    <option value="Perpustakaan">Perpustakaan</option>
                    <option value="Alat Himpunan">Alat Himpunan</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_total_stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Total Stok <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           id="edit_total_stock" 
                           name="total_stock" 
                           min="1" 
                           required 
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                </div>

                <div>
                    <label for="edit_available_stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Stok Tersedia <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           id="edit_available_stock" 
                           name="available_stock" 
                           min="0" 
                           required 
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                </div>
            </div>

            <div>
                <label for="edit_status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Status Barang <span class="text-rose-500">*</span>
                </label>
                <select id="edit_status" name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600">
                    <option value="available">Tersedia</option>
                    <option value="empty">Stok Habis</option>
                    <option value="maintenance">Dalam Perbaikan</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 3: Pratinjau QR Code -->
<div id="qrModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center border border-slate-200 shadow-xl relative animate-in fade-in zoom-in-95 duration-150">
        <button type="button" onclick="closeQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="mb-4">
            <span id="modalCategory" class="text-[11px] font-semibold uppercase tracking-wider text-indigo-600"></span>
            <h3 id="modalName" class="text-base font-bold text-slate-900 mt-0.5 leading-snug"></h3>
            <div id="modalCode" class="mt-1 font-mono text-xs font-bold text-slate-600"></div>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl inline-block mx-auto mb-4">
            <img id="modalQrImg" src="" alt="Kode QR Inventaris" class="w-48 h-48 mx-auto rounded-lg">
        </div>

        <p class="text-xs text-slate-500 mb-4">
            Stiker kode QR ini dapat dicetak dan ditempelkan pada fisik barang untuk dipindai oleh mahasiswa saat melakukan peminjaman.
        </p>

        <div class="flex items-center gap-2">
            <a id="modalPrintLink" href="#" class="flex-1 py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.37-2.16-.37-3.294 0-4.662 3.806-8.441 8.5-8.441s8.5 3.779 8.5 8.441c0 1.133-.13 2.238-.37 3.294M6.72 13.829a8.441 8.441 0 00-4.62 7.421h19.8c0-2.883-1.442-5.42-3.66-6.921M6.72 13.829A8.442 8.442 0 0112 11.25c2.072 0 3.966.75 5.42 2M6.75 21v-3.75h10.5V21" />
                </svg>
                Cetak Stiker Label
            </a>
            <button type="button" onclick="closeQrModal()" class="py-2 px-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createItemModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createItemModal').classList.add('hidden');
    }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/barang/" + item.id;
        document.getElementById('edit_code_label').textContent = "Kode QR: " + item.item_code;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_category').value = item.category;
        document.getElementById('edit_total_stock').value = item.total_stock;
        document.getElementById('edit_available_stock').value = item.available_stock;
        document.getElementById('edit_status').value = item.status;
        document.getElementById('editItemModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editItemModal').classList.add('hidden');
    }

    function openQrModal(id, code, name, category) {
        document.getElementById('modalCategory').textContent = category;
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalCode').textContent = code;
        
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(code);
        document.getElementById('modalQrImg').src = qrUrl;
        
        // Link to print page
        document.getElementById('modalPrintLink').href = "/admin/barang/" + id + "/cetak-qr";

        document.getElementById('qrModal').classList.remove('hidden');
    }
    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    // Backdrop clicks
    ['createItemModal', 'editItemModal', 'qrModal'].forEach(function(modalId) {
        document.getElementById(modalId)?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
