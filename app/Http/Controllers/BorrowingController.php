<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Store a newly created borrowing transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'borrow_date' => ['required', 'date'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'item_id.required' => 'Barang harus dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'borrow_date.required' => 'Tanggal pinjam wajib ditentukan.',
            'duration_days.required' => 'Durasi peminjaman wajib diisi.',
            'duration_days.min' => 'Durasi minimal 1 hari.',
            'duration_days.max' => 'Durasi maksimal 30 hari.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Use transaction to ensure safe stock decrement
        return DB::transaction(function () use ($validated, $user) {
            $item = Item::where('id', $validated['item_id'])->lockForUpdate()->firstOrFail();

            if (! $item->isAvailable()) {
                return back()->withErrors([
                    'item_id' => "Barang '{$item->name}' saat ini tidak tersedia atau stok sedang habis.",
                ])->withInput();
            }

            // Check if user already has an active borrowing for this same item
            $existingActive = Borrowing::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->whereIn('status', ['pending', 'borrowed'])
                ->first();

            if ($existingActive) {
                return back()->withErrors([
                    'item_id' => "Anda masih memiliki pengajuan/peminjaman aktif untuk barang '{$item->name}'.",
                ])->withInput();
            }

            $borrowDate = Carbon::parse($validated['borrow_date']);
            $dueDate = (clone $borrowDate)->addDays((int) $validated['duration_days']);

            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'return_date' => null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Decrement available stock
            $item->decrement('available_stock');
            if ($item->available_stock === 0) {
                $item->update(['status' => 'empty']);
            }

            return redirect()->route('borrowings.my')
                ->with('success', "Permintaan peminjaman '{$item->name}' berhasil diajukan! Menunggu verifikasi petugas.");
        });
    }

    /**
     * Display a listing of borrowings for the logged in student.
     */
    public function myBorrowings(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Borrowing::with('item')
            ->where('user_id', $user->id)
            ->latest();

        $filter = $request->input('status', 'all');
        if ($filter === 'active') {
            $query->whereIn('status', ['pending', 'borrowed']);
        } elseif ($filter === 'completed') {
            $query->whereIn('status', ['returned', 'rejected']);
        }

        $borrowings = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Borrowing::where('user_id', $user->id)->count(),
            'active' => Borrowing::where('user_id', $user->id)->whereIn('status', ['pending', 'borrowed'])->count(),
            'returned' => Borrowing::where('user_id', $user->id)->where('status', 'returned')->count(),
        ];

        return view('borrowings.my', [
            'borrowings' => $borrowings,
            'currentFilter' => $filter,
            'stats' => $stats,
        ]);
    }
}
