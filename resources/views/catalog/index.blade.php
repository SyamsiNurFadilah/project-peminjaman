@extends('layouts.app')

@section('title', 'Katalog Inventaris - Sistem Peminjaman Mandiri Kampus')

@section('content')
<div class="space-y-6">

    <!-- Hero / Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-600/10 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    Inventaris Terintegrasi QR Code
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Katalog Barang & Inventaris</h1>
                <p class="text-slate-500 text-sm mt-1 max-w-2xl">
                    Jelajahi peralatan lab, koleksi perpustakaan, dan fasilitas himpunan mahasiswa. Pindai kode QR fisik pada barang untuk melakukan peminjaman mandiri secara instan.
                </p>
            </div>

            <!-- Quick Action CTA (Only for student borrowers / guests) -->
            @if(!auth()->check() || !auth()->user()->isAdmin())
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('scan.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm shadow-blue-500/10 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        Buka Scan Mandiri
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-100 text-center sm:text-left">
            <div>
                <div class="text-xs text-slate-500 font-medium">Total Inventaris</div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5">{{ $stats['total_items'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Siap Dipinjam</div>
                <div class="text-xl sm:text-2xl font-bold text-emerald-600 mt-0.5">{{ $stats['available_count'] }} <span class="text-xs font-normal text-slate-400">item</span></div>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Kategori Fasilitas</div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5">{{ $stats['categories_count'] }} <span class="text-xs font-normal text-slate-400">bidang</span></div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Category Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <a href="{{ route('catalog.index', array_merge(request()->except(['category', 'page']), ['category' => ''])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', array_merge(request()->except(['category', 'page']), ['category' => $cat])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $selectedCategory === $cat ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Search and Status Filter -->
            <div class="flex items-center gap-2">
                @if(!empty($selectedCategory))
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-300 text-xs font-medium text-slate-700 py-2 px-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                    <option value="">Semua Status</option>
                    <option value="available" {{ $selectedStatus === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="empty" {{ $selectedStatus === 'empty' ? 'selected' : '' }}>Stok Habis</option>
                    <option value="maintenance" {{ $selectedStatus === 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                </select>

                <div class="relative flex-1 sm:w-64">
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari nama atau kode QR..." 
                           class="w-full rounded-xl border border-slate-300 pl-8 pr-3 py-1.5 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <button type="submit" class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Cari
                </button>

                @if(!empty($searchQuery) || !empty($selectedCategory) || !empty($selectedStatus))
                    <a href="{{ route('catalog.index') }}" title="Reset Filter" class="p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition text-xs font-medium">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($items as $item)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:border-slate-300 transition duration-150 ease-in-out flex flex-col justify-between group">
                    <div>
                        <!-- Card Header: Category & Status Badge -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                {{ $item->category }}
                            </span>

                            @if($item->status === 'available')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Tersedia ({{ $item->available_stock }})
                                </span>
                            @elseif($item->status === 'empty')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                    Habis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                    Perbaikan
                                </span>
                            @endif
                        </div>

                        <!-- Item Title -->
                        <h2 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition leading-snug">
                            {{ $item->name }}
                        </h2>

                        <!-- Item Code (QR Identifier) -->
                        <div class="mt-2 flex items-center gap-2">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 font-mono text-xs font-semibold">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                </svg>
                                <span>{{ $item->item_code }}</span>
                            </div>

                            <button type="button" 
                                    onclick="openQrModal('{{ $item->item_code }}', '{{ addslashes($item->name) }}', '{{ $item->category }}')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 transition">
                                <span>Lihat QR</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </button>
                        </div>

                        <!-- Stock Bar -->
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                                <span>Ketersediaan Unit</span>
                                <span class="font-semibold text-slate-700">{{ $item->available_stock }} / {{ $item->total_stock }} unit</span>
                            </div>
                            @php
                                $percentage = $item->total_stock > 0 ? ($item->available_stock / $item->total_stock) * 100 : 0;
                            @endphp
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $item->available_stock > 0 ? 'bg-blue-600' : 'bg-slate-300' }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Bottom -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('catalog.show', $item) }}" 
                               class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs text-center transition flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lihat Detail Barang
                            </a>
                        @else
                            @if($item->isAvailable())
                                <a href="{{ route('scan.index', ['code' => $item->item_code]) }}" 
                                   class="w-full py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-semibold text-xs text-center transition flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                    </svg>
                                    Pinjam via Scan QR
                                </a>
                            @else
                                <button disabled 
                                        class="w-full py-2 px-3 rounded-xl bg-slate-100 text-slate-400 font-semibold text-xs text-center cursor-not-allowed">
                                    {{ $item->status === 'empty' ? 'Stok Tidak Tersedia' : 'Dalam Perbaikan' }}
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center shadow-xs">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak ada barang yang ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Coba sesuaikan kata kunci pencarian atau ubah filter kategori untuk menemukan barang inventaris yang Anda cari.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-block mt-4 text-xs font-semibold text-blue-600 hover:underline">
                Tampilkan Semua Barang
            </a>
        </div>
    @endif

</div>

<!-- QR Code Modal Preview -->
<div id="qrModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center border border-slate-200 shadow-xl relative animate-in fade-in zoom-in-95 duration-150">
        <!-- Close Button -->
        <button type="button" onclick="closeQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="mb-4">
            <span id="modalCategory" class="text-[11px] font-semibold uppercase tracking-wider text-slate-500"></span>
            <h3 id="modalName" class="text-base font-bold text-slate-900 mt-0.5 leading-snug"></h3>
            <div id="modalCode" class="mt-1 font-mono text-xs font-bold text-blue-600"></div>
        </div>

        <!-- Generated QR Code Image -->
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl inline-block mx-auto mb-4">
            <img id="modalQrImg" src="" alt="Kode QR Inventaris" class="w-48 h-48 mx-auto rounded-lg">
        </div>

        <p class="text-xs text-slate-500 mb-4">
            @if(auth()->check() && auth()->user()->isAdmin())
                Pratinjau kode QR inventaris kampus. Mahasiswa memindai kode ini menggunakan kamera scanner untuk meminjam mandiri.
            @else
                Arahkan kamera scanner pada menu <strong>Scan Mandiri</strong> ke kode QR di atas untuk mengisi formulir secara otomatis.
            @endif
        </p>

        <div class="flex items-center gap-2">
            @if(!auth()->check() || !auth()->user()->isAdmin())
                <a id="modalScanLink" href="#" class="flex-1 py-2 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition">
                    Gunakan Kode Ini di Scan
                </a>
            @endif
            <button type="button" onclick="closeQrModal()" class="py-2 px-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition {{ auth()->check() && auth()->user()->isAdmin() ? 'w-full' : '' }}">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openQrModal(code, name, category) {
        document.getElementById('modalCategory').textContent = category;
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalCode').textContent = code;
        
        // Fast dynamic QR image URL using standard QR code generator
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(code);
        document.getElementById('modalQrImg').src = qrUrl;

        // Set action link
        document.getElementById('modalScanLink').href = "{{ route('scan.index') }}?code=" + encodeURIComponent(code);

        document.getElementById('qrModal').classList.remove('hidden');
    }

    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    // Close on click backdrop
    document.getElementById('qrModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeQrModal();
        }
    });
</script>
@endpush
@endsection
