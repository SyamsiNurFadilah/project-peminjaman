<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanController extends Controller
{
    /**
     * Show the QR code scanner and self-service loan interface.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Halaman scan mandiri hanya untuk peminjam (mahasiswa). Admin bertugas di panel manajemen inventaris.');
        }

        $prefilledItem = null;
        if ($request->filled('code')) {
            $prefilledItem = Item::where('item_code', $request->string('code')->trim())->first();
        }

        return view('scan.index', [
            'prefilledItem' => $prefilledItem,
            'recentItems' => Item::where('status', 'available')->where('available_stock', '>', 0)->latest()->limit(5)->get(),
        ]);
    }

    /**
     * Lookup an item by its QR/barcode item_code for auto-filling the loan form.
     */
    public function checkItem(string $code): JsonResponse
    {
        $code = trim($code);

        $item = Item::where('item_code', $code)->first();

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => "Kode barang '{$code}' tidak ditemukan di sistem inventaris.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diverifikasi.',
            'item' => [
                'id' => $item->id,
                'item_code' => $item->item_code,
                'name' => $item->name,
                'category' => $item->category,
                'total_stock' => $item->total_stock,
                'available_stock' => $item->available_stock,
                'status' => $item->status,
                'is_available' => $item->isAvailable(),
            ],
        ]);
    }
}
