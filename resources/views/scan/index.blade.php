@extends('layouts.app')

@section('title', 'Scan Mandiri QR Code - Sistem Peminjaman Mandiri Kampus')

@push('styles')
<style>
    #reader video {
        border-radius: 0.75rem;
        object-fit: cover;
        width: 100% !important;
    }
    #reader {
        border: none !important;
    }
    #reader__scan_region {
        background: rgba(15, 23, 42, 0.03);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 ring-1 ring-blue-600/10 mb-2">
                    Pemindaian Otomatis
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Scan Mandiri & Form Peminjaman</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Arahkan kamera ke stiker QR Code pada barang inventaris atau pilih opsi input manual untuk mengisi formulir peminjaman.
                </p>
            </div>

            <!-- Steps indicator -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <span class="flex items-center gap-1 text-blue-600">
                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">1</span>
                    Scan QR
                </span>
                <span>&rarr;</span>
                <span class="flex items-center gap-1 text-slate-700">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-[10px] font-bold">2</span>
                    Verifikasi
                </span>
                <span>&rarr;</span>
                <span class="flex items-center gap-1 text-slate-500">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">3</span>
                    Pinjam
                </span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Scanner on Left, Loan Form on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: QR Scanner & Methods (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                
                <!-- Method Tabs -->
                <div class="flex rounded-xl bg-slate-100 p-1 mb-4 text-xs font-semibold">
                    <button type="button" 
                            id="tabBtnCamera" 
                            onclick="switchScanTab('camera')"
                            class="flex-1 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        </svg>
                        Kamera Langsung
                    </button>
                    <button type="button" 
                            id="tabBtnUpload" 
                            onclick="switchScanTab('upload')"
                            class="flex-1 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Unggah Foto QR
                    </button>
                    <button type="button" 
                            id="tabBtnManual" 
                            onclick="switchScanTab('manual')"
                            class="flex-1 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Ketik Kode
                    </button>
                </div>

                <!-- Tab 1: Camera Scanner View -->
                <div id="scanCameraPane" class="space-y-3">
                    <div class="relative overflow-hidden rounded-xl bg-slate-900 aspect-square flex items-center justify-center border border-slate-200">
                        <div id="reader" class="w-full h-full"></div>

                        <!-- Placeholder when camera is inactive -->
                        <div id="cameraPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-white bg-slate-900/90 z-10">
                            <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mb-3 text-slate-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold">Kamera Belum Aktif</h4>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs">
                                Klik tombol di bawah untuk mengizinkan akses kamera browser Anda.
                            </p>
                            <button type="button" 
                                    id="btnStartCam" 
                                    onclick="startCamera()"
                                    class="mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                </svg>
                                Nyalakan Kamera
                            </button>
                        </div>
                    </div>

                    <!-- Camera Control Bar (Active State) -->
                    <div id="cameraControls" class="hidden flex items-center justify-between gap-2 pt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-emerald-600 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            Kamera Aktif & Memindai...
                        </span>
                        <button type="button" 
                                onclick="stopCamera()" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                            Hentikan Kamera
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Upload QR Image Pane -->
                <div id="scanUploadPane" class="hidden space-y-3">
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition bg-slate-50/50">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <label for="qrFileInput" class="cursor-pointer text-xs font-semibold text-blue-600 hover:underline">
                            Pilih file gambar QR Code
                        </label>
                        <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, atau WEBP hasil foto kode QR</p>
                        <input type="file" id="qrFileInput" accept="image/*" class="hidden" onchange="handleFileUpload(this)">
                    </div>
                    <div id="uploadFeedback" class="text-xs text-center font-medium hidden"></div>
                </div>

                <!-- Tab 3: Manual Input Pane -->
                <div id="scanManualPane" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Kode String Barang (Item Code)
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   id="manualCodeInput" 
                                   placeholder="Contoh: INV-LIB-042" 
                                   class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                            <button type="button" 
                                    onclick="triggerManualLookup()" 
                                    class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition">
                                Cek
                            </button>
                        </div>
                    </div>

                    <!-- Quick Sample Chips for Fast Testing -->
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider block mb-1.5">
                            Coba Kode Cepat (Klik untuk Simulasi):
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($recentItems as $rItem)
                                <button type="button" 
                                        onclick="lookupItemCode('{{ $rItem->item_code }}')" 
                                        class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700 font-mono text-[11px] border border-slate-200 transition">
                                    {{ $rItem->item_code }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Scan Status / Feedback Banner -->
            <div id="scanStatusBox" class="p-3.5 rounded-xl text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-slate-400" id="statusDot"></span>
                <span id="statusText">Scanner siap. Pindai QR Code untuk memulai.</span>
            </div>
        </div>

        <!-- Right: Loan Application Form (7 cols) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Formulir Peminjaman Mandiri</h2>
                        <p class="text-xs text-slate-500">Konfirmasi detail barang dan durasi sebelum meminjam.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Peminjam</span>
                        <span class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                <!-- Verified Item Information Box (Auto-Filled) -->
                <div id="itemPreviewBox" class="mb-6 p-4 rounded-xl border {{ $prefilledItem ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50' }} transition-all">
                    
                    @if($prefilledItem)
                        <div id="verifiedContent">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-100/60 px-2 py-0.5 rounded-md">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Barang Terverifikasi
                                </span>
                                <span class="font-mono text-xs font-bold text-slate-600">{{ $prefilledItem->item_code }}</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="previewItemName">{{ $prefilledItem->name }}</h3>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                <span>Kategori: <strong class="text-slate-700" id="previewItemCat">{{ $prefilledItem->category }}</strong></span>
                                <span>•</span>
                                <span>Stok Tersedia: <strong class="text-emerald-600" id="previewItemStock">{{ $prefilledItem->available_stock }} Unit</strong></span>
                            </div>
                        </div>
                    @else
                        <div id="emptyContent" class="text-center py-4">
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="text-xs font-semibold text-slate-700">Belum Ada Barang Terpilih</div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Arahkan kamera atau ketik kode QR di sebelah kiri untuk mendeteksi barang otomatis.
                            </p>
                        </div>

                        <div id="verifiedContent" class="hidden">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-100/60 px-2 py-0.5 rounded-md">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Barang Terverifikasi
                                </span>
                                <span class="font-mono text-xs font-bold text-slate-600" id="previewItemCode"></span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="previewItemName"></h3>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                <span>Kategori: <strong class="text-slate-700" id="previewItemCat"></strong></span>
                                <span>•</span>
                                <span>Stok Tersedia: <strong class="text-emerald-600" id="previewItemStock"></strong></span>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Submission Form -->
                <form method="POST" action="{{ route('borrowings.store') }}" id="loanForm" class="space-y-4">
                    @csrf

                    <!-- Target Item ID (hidden) -->
                    <input type="hidden" name="item_id" id="formItemId" value="{{ $prefilledItem ? $prefilledItem->id : old('item_id') }}" required>

                    <!-- Student Info (Read-only) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                                Nama Mahasiswa
                            </label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                                NIM / NIP
                            </label>
                            <input type="text" value="{{ Auth::user()->nim_nip }}" disabled 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-mono font-medium text-slate-700 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Borrow Date & Duration -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div>
                            <label for="borrow_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Tanggal Mulai Pinjam
                            </label>
                            <input type="date" 
                                   id="borrow_date" 
                                   name="borrow_date" 
                                   value="{{ old('borrow_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}"
                                   onchange="updateDueDate()"
                                   required 
                                   class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Durasi Peminjaman (Hari)
                            </label>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="setDuration(3)" class="duration-btn flex-1 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">3 Hari</button>
                                <button type="button" onclick="setDuration(7)" class="duration-btn flex-1 py-2 rounded-xl border border-blue-600 bg-blue-50 text-blue-700 text-xs font-semibold">7 Hari</button>
                                <button type="button" onclick="setDuration(14)" class="duration-btn flex-1 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">14 Hari</button>
                            </div>
                            <input type="hidden" name="duration_days" id="durationDays" value="{{ old('duration_days', 7) }}" required>
                        </div>
                    </div>

                    <!-- Calculated Due Date Banner -->
                    <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between text-xs">
                        <span class="text-blue-900 font-medium">Batas Waktu Pengembalian:</span>
                        <strong class="text-blue-700 font-bold" id="dueDatePreview">-</strong>
                    </div>

                    <!-- Loan Notes -->
                    <div>
                        <label for="notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Keperluan Peminjaman (Opsional)
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="2" 
                                  placeholder="Contoh: Digunakan untuk praktikum mata kuliah Pemrograman Web di Lab 2" 
                                  class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                id="btnSubmitLoan" 
                                {{ $prefilledItem ? '' : 'disabled' }}
                                class="w-full py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 ease-in-out cursor-pointer flex items-center justify-center gap-2 {{ $prefilledItem ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/10' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Ajukan Peminjaman Sekarang
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<!-- Load HTML5-QRCode via CDN for camera and image scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let isCameraActive = false;

    // Document ready
    document.addEventListener('DOMContentLoaded', () => {
        updateDueDate();

        @if($prefilledItem)
            setStatus('success', "Barang '{{ $prefilledItem->name }}' berhasil dimuat dari katalog.");
        @endif
    });

    // Switch between Camera, Upload, and Manual tabs
    function switchScanTab(tab) {
        document.getElementById('scanCameraPane').classList.add('hidden');
        document.getElementById('scanUploadPane').classList.add('hidden');
        document.getElementById('scanManualPane').classList.add('hidden');

        document.getElementById('tabBtnCamera').className = 'flex-1 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5';
        document.getElementById('tabBtnUpload').className = 'flex-1 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5';
        document.getElementById('tabBtnManual').className = 'flex-1 py-1.5 rounded-lg transition text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5';

        if (tab === 'camera') {
            document.getElementById('scanCameraPane').classList.remove('hidden');
            document.getElementById('tabBtnCamera').className = 'flex-1 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5';
        } else if (tab === 'upload') {
            document.getElementById('scanUploadPane').classList.remove('hidden');
            document.getElementById('tabBtnUpload').className = 'flex-1 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5';
            if (isCameraActive) stopCamera();
        } else if (tab === 'manual') {
            document.getElementById('scanManualPane').classList.remove('hidden');
            document.getElementById('tabBtnManual').className = 'flex-1 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5';
            if (isCameraActive) stopCamera();
            document.getElementById('manualCodeInput').focus();
        }
    }

    // Start Webcam QR Scanner
    function startCamera() {
        if (typeof Html5Qrcode === 'undefined') {
            alert('Pustaka scanner QR sedang dimuat, mohon coba sebentar lagi.');
            return;
        }

        const qrRegion = "reader";
        html5QrCode = new Html5Qrcode(qrRegion);

        const config = {
            fps: 10,
            qrbox: { width: 220, height: 220 },
            aspectRatio: 1.0
        };

        setStatus('info', 'Mengakses kamera perangkat...');

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // Success QR Read
                playScanBeep();
                setStatus('success', `Kode terdeteksi: ${decodedText}`);
                lookupItemCode(decodedText);
            },
            (errorMessage) => {
                // scanning frame error (ignore continuous scan noise)
            }
        ).then(() => {
            isCameraActive = true;
            document.getElementById('cameraPlaceholder').classList.add('hidden');
            document.getElementById('cameraControls').classList.remove('hidden');
            setStatus('info', 'Arahkan kamera ke QR Code barang.');
        }).catch((err) => {
            console.error(err);
            setStatus('error', 'Gagal mengakses kamera. Pastikan izin kamera aktif atau gunakan tab Unggah Foto/Ketik Kode.');
        });
    }

    // Stop Webcam Scanner
    function stopCamera() {
        if (html5QrCode && isCameraActive) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                isCameraActive = false;
                document.getElementById('cameraPlaceholder').classList.remove('hidden');
                document.getElementById('cameraControls').classList.add('hidden');
                setStatus('info', 'Kamera dihentikan.');
            }).catch(err => console.error(err));
        }
    }

    // Handle File Upload QR Scanner
    function handleFileUpload(input) {
        if (!input.files || input.files.length === 0) return;
        const file = input.files[0];

        if (typeof Html5Qrcode === 'undefined') {
            alert('Pustaka scanner QR belum siap.');
            return;
        }

        const scanner = new Html5Qrcode("reader");
        setStatus('info', 'Membaca gambar QR code...');

        scanner.scanFile(file, true)
            .then(decodedText => {
                playScanBeep();
                setStatus('success', `QR berhasil dipindai dari file: ${decodedText}`);
                lookupItemCode(decodedText);
            })
            .catch(err => {
                console.error(err);
                setStatus('error', 'Tidak ditemukan kode QR valid pada gambar tersebut. Silakan coba gambar lain.');
            });
    }

    // Trigger Manual Input
    function triggerManualLookup() {
        const code = document.getElementById('manualCodeInput').value.trim();
        if (!code) {
            alert('Silakan ketik kode barang terlebih dahulu.');
            return;
        }
        lookupItemCode(code);
    }

    // AJAX API Call to verify item and auto-fill loan form
    function lookupItemCode(itemCode) {
        setStatus('info', `Memverifikasi kode ${itemCode} ke server...`);

        fetch(`/api/items/${encodeURIComponent(itemCode)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                const item = body.item;

                if (!item.is_available) {
                    setStatus('error', `Barang '${item.name}' tidak dapat dipinjam (Stok habis atau dalam pemeliharaan).`);
                    resetFormState();
                    return;
                }

                // Populate Form Fields
                document.getElementById('formItemId').value = item.id;
                document.getElementById('previewItemName').textContent = item.name;
                document.getElementById('previewItemCat').textContent = item.category;
                document.getElementById('previewItemStock').textContent = `${item.available_stock} Unit`;
                document.getElementById('previewItemCode').textContent = item.item_code;

                // Toggle Preview Box
                const emptyContent = document.getElementById('emptyContent');
                if (emptyContent) emptyContent.classList.add('hidden');
                document.getElementById('verifiedContent').classList.remove('hidden');

                const previewBox = document.getElementById('itemPreviewBox');
                previewBox.className = 'mb-6 p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 transition-all';

                // Enable Submit Button
                const btnSubmit = document.getElementById('btnSubmitLoan');
                btnSubmit.disabled = false;
                btnSubmit.className = 'w-full py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 ease-in-out cursor-pointer flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/10';

                setStatus('success', `Barang '${item.name}' berhasil diverifikasi dan siap dipinjam!`);
            } else {
                setStatus('error', body.message || 'Kode barang tidak ditemukan di basis data kampus.');
                resetFormState();
            }
        })
        .catch(err => {
            console.error(err);
            setStatus('error', 'Terjadi gangguan jaringan saat memverifikasi barang.');
        });
    }

    function resetFormState() {
        document.getElementById('formItemId').value = '';
        const emptyContent = document.getElementById('emptyContent');
        if (emptyContent) emptyContent.classList.remove('hidden');
        document.getElementById('verifiedContent').classList.add('hidden');

        const previewBox = document.getElementById('itemPreviewBox');
        previewBox.className = 'mb-6 p-4 rounded-xl border border-slate-200 bg-slate-50 transition-all';

        const btnSubmit = document.getElementById('btnSubmitLoan');
        btnSubmit.disabled = true;
        btnSubmit.className = 'w-full py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 ease-in-out cursor-not-allowed flex items-center justify-center gap-2 bg-slate-100 text-slate-400';
    }

    // Set Duration in days and update preview
    function setDuration(days) {
        document.getElementById('durationDays').value = days;
        
        // Update button styles
        const buttons = document.querySelectorAll('.duration-btn');
        buttons.forEach(btn => {
            if (btn.textContent.includes(days + ' Hari')) {
                btn.className = 'duration-btn flex-1 py-2 rounded-xl border border-blue-600 bg-blue-50 text-blue-700 text-xs font-semibold';
            } else {
                btn.className = 'duration-btn flex-1 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50';
            }
        });

        updateDueDate();
    }

    // Calculate Due Date based on borrow date + duration
    function updateDueDate() {
        const borrowDateVal = document.getElementById('borrow_date').value;
        const duration = parseInt(document.getElementById('durationDays').value) || 7;

        if (borrowDateVal) {
            const borrowDate = new Date(borrowDateVal);
            borrowDate.setDate(borrowDate.getDate() + duration);

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('dueDatePreview').textContent = borrowDate.toLocaleDateString('id-ID', options);
        }
    }

    // Visual Status Message Helper
    function setStatus(type, message) {
        const box = document.getElementById('scanStatusBox');
        const dot = document.getElementById('statusDot');
        const text = document.getElementById('statusText');

        text.textContent = message;

        if (type === 'success') {
            box.className = 'p-3.5 rounded-xl text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-2.5';
            dot.className = 'w-2 h-2 rounded-full bg-emerald-500';
        } else if (type === 'error') {
            box.className = 'p-3.5 rounded-xl text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200 flex items-center gap-2.5';
            dot.className = 'w-2 h-2 rounded-full bg-rose-500';
        } else {
            box.className = 'p-3.5 rounded-xl text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200 flex items-center gap-2.5';
            dot.className = 'w-2 h-2 rounded-full bg-blue-500 animate-pulse';
        }
    }

    // Subtle audio feedback on successful QR scan
    function playScanBeep() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.15);
        } catch (e) {
            // Audio context blocked or not supported
        }
    }
</script>
@endpush
@endsection
