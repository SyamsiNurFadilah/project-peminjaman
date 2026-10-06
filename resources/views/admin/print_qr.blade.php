@extends('layouts.app')

@section('title', 'Cetak Label QR - ' . $item->name)

@push('styles')
<style>
    @media print {
        /* Hide navbar, footer, actions, and flash messages during printing */
        header, footer, .no-print, nav, #flash-container {
            display: none !important;
        }
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        main {
            padding: 0 !important;
            max-width: 100% !important;
        }
        .print-sheet {
            box-shadow: none !important;
            border: 2px dashed #94a3b8 !important;
            margin: 0 auto !important;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('admin.items') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Kelola Barang
        </a>

        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="window.print()" 
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm shadow-indigo-500/10 flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.37-2.16-.37-3.294 0-4.662 3.806-8.441 8.5-8.441s8.5 3.779 8.5 8.441c0 1.133-.13 2.238-.37 3.294M6.72 13.829a8.441 8.441 0 00-4.62 7.421h19.8c0-2.883-1.442-5.42-3.66-6.921M6.72 13.829A8.442 8.442 0 0112 11.25c2.072 0 3.966.75 5.42 2M6.75 21v-3.75h10.5V21" />
                </svg>
                Cetak Stiker Label Sekarang
            </button>
        </div>
    </div>

    <!-- Official Printable Sticker Label Card -->
    <div class="print-sheet bg-white rounded-2xl border-2 border-slate-300 p-8 shadow-sm text-center relative max-w-md mx-auto">
        
        <!-- Header Institution Badge -->
        <div class="border-b-2 border-slate-900 pb-3 mb-5">
            <div class="flex items-center justify-center gap-2 mb-1">
                <div class="w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                    PK
                </div>
                <span class="font-bold text-slate-900 text-xs tracking-wider uppercase">Inventaris Kampus Mandiri</span>
            </div>
            <div class="text-[11px] text-slate-600 font-medium">Stiker Identifikasi Aset & Kode QR Resmi</div>
        </div>

        <!-- High Resolution QR Code -->
        <div class="p-3 bg-white border border-slate-300 rounded-xl inline-block mx-auto mb-4 shadow-2xs">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($item->item_code) }}" 
                 alt="QR Code {{ $item->item_code }}" 
                 class="w-52 h-52 mx-auto rounded-lg">
        </div>

        <!-- Item Identity Info -->
        <div class="space-y-1.5 mb-4">
            <div class="font-mono text-base font-extrabold text-slate-900 tracking-wider">
                {{ $item->item_code }}
            </div>
            <h2 class="text-lg font-bold text-slate-900 leading-snug">
                {{ $item->name }}
            </h2>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                Kategori: {{ $item->category }}
            </div>
        </div>

        <!-- Footer / Instructions -->
        <div class="border-t border-dashed border-slate-300 pt-3 text-[11px] text-slate-500 leading-relaxed">
            <p class="font-medium text-slate-700">Petunjuk Peminjaman Mandiri:</p>
            <p>Buka menu <strong>Scan Mandiri</strong> pada web PinjamKampus dan arahkan kamera ke kode QR ini untuk mengisi formulir peminjaman.</p>
            <p class="text-[10px] text-slate-400 mt-2 font-mono">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>

    </div>

    <!-- Instructions note (no-print) -->
    <div class="no-print bg-indigo-50/70 border border-indigo-200/80 rounded-2xl p-5 text-indigo-900 text-xs shadow-xs">
        <div class="flex items-start gap-2.5">
            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
                <strong class="font-bold text-indigo-950 block mb-0.5">Tips Pencetakan Stiker Label:</strong>
                <p class="text-indigo-800 leading-relaxed">
                    Saat dialog printer terbuka, Anda dapat memilih ukuran kertas stiker (Label Sticker A4 atau Thermal 80mm). 
                    Pastikan opsi <em>Background graphics</em> dicentang pada setelan cetak peramban agar border dan kode QR tercetak dengan tajam dan sempurna.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
