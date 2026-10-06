<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with statistics and pending actions.
     */
    public function dashboard(): View
    {
        $stats = [
            'total_borrowings' => Borrowing::count(),
            'pending_borrowings' => Borrowing::where('status', 'pending')->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'returned_borrowings' => Borrowing::where('status', 'returned')->count(),
            'rejected_borrowings' => Borrowing::where('status', 'rejected')->count(),
            'total_items' => Item::count(),
            'available_items' => Item::where('status', 'available')->where('available_stock', '>', 0)->count(),
            'empty_items' => Item::where('status', 'empty')->orWhere('available_stock', 0)->count(),
            'maintenance_items' => Item::where('status', 'maintenance')->count(),
        ];

        $pendingBorrowings = Borrowing::with(['user', 'item'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $activeBorrowings = Borrowing::with(['user', 'item'])
            ->where('status', 'borrowed')
            ->latest('due_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'pendingBorrowings' => $pendingBorrowings,
            'activeBorrowings' => $activeBorrowings,
        ]);
    }

    /**
     * Manage and verify all borrowings.
     */
    public function borrowings(Request $request): View
    {
        $query = Borrowing::with(['user', 'item'])->latest();

        $status = $request->input('status', 'all');
        if ($status !== 'all' && in_array($status, ['pending', 'borrowed', 'returned', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->trim();
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('nim_nip', 'like', "%{$search}%");
                })->orWhereHas('item', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                        ->orWhere('item_code', 'like', "%{$search}%");
                });
            });
        }

        $borrowings = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Borrowing::count(),
            'pending' => Borrowing::where('status', 'pending')->count(),
            'borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
            'rejected' => Borrowing::where('status', 'rejected')->count(),
        ];

        return view('admin.borrowings', [
            'borrowings' => $borrowings,
            'currentStatus' => $status,
            'searchQuery' => $request->input('q', ''),
            'counts' => $counts,
        ]);
    }

    /**
     * Approve a pending borrowing transaction.
     */
    public function approveBorrowing(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman berstatus pending yang dapat disetujui.');
        }

        $borrowing->update([
            'status' => 'borrowed',
        ]);

        return back()->with('success', "Peminjaman barang '{$borrowing->item->name}' untuk {$borrowing->user->name} berhasil disetujui!");
    }

    /**
     * Reject a pending borrowing transaction and restore stock.
     */
    public function rejectBorrowing(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman berstatus pending yang dapat ditolak.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'rejected',
            ]);

            // Restore the item available stock
            $item = $borrowing->item;
            $item->increment('available_stock');
            if ($item->status === 'empty' && $item->available_stock > 0) {
                $item->update(['status' => 'available']);
            }
        });

        return back()->with('success', 'Pengajuan peminjaman ditolak dan 1 unit stok barang telah dikembalikan.');
    }

    /**
     * Confirm return of borrowed item and restore stock.
     */
    public function returnBorrowing(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Hanya barang yang sedang dipinjam yang dapat dikonfirmasi pengembaliannya.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);

            // Restore the item stock
            $item = $borrowing->item;
            $item->increment('available_stock');
            if ($item->status === 'empty' && $item->available_stock > 0) {
                $item->update(['status' => 'available']);
            }
        });

        return back()->with('success', "Pengembalian barang '{$borrowing->item->name}' dari {$borrowing->user->name} berhasil dikonfirmasi. Stok barang telah bertambah.");
    }

    /**
     * Display inventory management screen.
     */
    public function items(Request $request): View
    {
        $categories = ['Lab', 'Perpustakaan', 'Alat Himpunan'];
        $statuses = ['available' => 'Tersedia', 'empty' => 'Habis', 'maintenance' => 'Perbaikan'];
        $query = Item::query()->latest();

        if ($request->filled('q')) {
            $search = $request->string('q')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && in_array($request->input('category'), $categories, true)) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status') && array_key_exists($request->input('status'), $statuses)) {
            $query->where('status', $request->input('status'));
        }

        $items = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Item::count(),
            'available' => Item::where('status', 'available')->count(),
            'empty' => Item::where('status', 'empty')->count(),
            'maintenance' => Item::where('status', 'maintenance')->count(),
        ];

        return view('admin.items', [
            'items' => $items,
            'categories' => $categories,
            'selectedCategory' => $request->input('category', ''),
            'selectedStatus' => $request->input('status', ''),
            'searchQuery' => $request->input('q', ''),
            'counts' => $counts,
        ]);
    }

    /**
     * Store newly created item with generated or custom QR code.
     */
    public function storeItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Lab,Perpustakaan,Alat Himpunan'],
            'total_stock' => ['required', 'integer', 'min:1'],
            'item_code' => ['nullable', 'string', 'max:50', 'unique:items,item_code'],
            'status' => ['required', 'string', 'in:available,empty,maintenance'],
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'total_stock.required' => 'Jumlah stok wajib diisi.',
            'item_code.unique' => 'Kode barang / QR sudah digunakan pada barang lain.',
        ]);

        // Auto-generate item_code if left empty
        if (empty($validated['item_code'])) {
            $prefixMap = ['Lab' => 'INV-LAB', 'Perpustakaan' => 'INV-LIB', 'Alat Himpunan' => 'INV-HMP'];
            $prefix = $prefixMap[$validated['category']] ?? 'INV-GEN';
            do {
                $code = $prefix.'-'.strtoupper(bin2hex(random_bytes(2)));
            } while (Item::where('item_code', $code)->exists());
            $validated['item_code'] = $code;
        } else {
            $validated['item_code'] = strtoupper(trim($validated['item_code']));
        }

        $validated['available_stock'] = $validated['total_stock'];

        $item = Item::create($validated);

        return back()->with('success', "Barang baru '{$item->name}' dengan kode QR '{$item->item_code}' berhasil ditambahkan!");
    }

    /**
     * Update existing item details.
     */
    public function updateItem(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Lab,Perpustakaan,Alat Himpunan'],
            'total_stock' => ['required', 'integer', 'min:1'],
            'available_stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:available,empty,maintenance'],
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'total_stock.required' => 'Total stok wajib diisi.',
            'available_stock.required' => 'Stok tersedia wajib diisi.',
        ]);

        if ($validated['available_stock'] > $validated['total_stock']) {
            return back()->withErrors(['available_stock' => 'Stok tersedia tidak boleh melebihi total stok barang.'])->withInput();
        }

        if ($validated['status'] === 'available' && $validated['available_stock'] === 0) {
            $validated['status'] = 'empty';
        } elseif ($validated['status'] === 'empty' && $validated['available_stock'] > 0) {
            $validated['status'] = 'available';
        }

        $item->update($validated);

        return back()->with('success', "Data barang '{$item->name}' berhasil diperbarui.");
    }

    /**
     * Delete an inventory item.
     */
    public function destroyItem(Item $item): RedirectResponse
    {
        $activeBorrowings = $item->borrowings()->whereIn('status', ['pending', 'borrowed'])->count();
        if ($activeBorrowings > 0) {
            return back()->with('error', "Barang '{$item->name}' tidak dapat dihapus karena masih ada {$activeBorrowings} transaksi peminjaman yang aktif atau menunggu verifikasi.");
        }

        $name = $item->name;
        $item->delete();

        return back()->with('success', "Barang '{$name}' telah dihapus dari inventaris.");
    }

    /**
     * Print sheet view for QR Code.
     */
    public function printQr(Item $item): View
    {
        return view('admin.print_qr', [
            'item' => $item,
        ]);
    }

    /**
     * Display list of admins/staff and registration form.
     */
    public function admins(Request $request): View
    {
        $query = User::where('role', 'admin')->latest();

        if ($request->filled('q')) {
            $search = $request->string('q')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim_nip', 'like', "%{$search}%");
            });
        }

        $admins = $query->paginate(10)->withQueryString();

        return view('admin.admins', [
            'admins' => $admins,
            'searchQuery' => $request->input('q', ''),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
        ]);
    }

    /**
     * Register a new admin/staff member.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim_nip' => ['required', 'string', 'max:50', 'unique:users,nim_nip'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap petugas wajib diisi.',
            'nim_nip.required' => 'NIP / Kode Petugas wajib diisi.',
            'nim_nip.unique' => 'NIP / Kode Petugas ini sudah terdaftar.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus :min karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $admin = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'nim_nip' => trim($validated['nim_nip']),
        ]);

        return back()->with('success', "Petugas / Administrator baru '{$admin->name}' (NIP: {$admin->nim_nip}) berhasil didaftarkan.");
    }

    /**
     * Remove an admin/staff account.
     */
    public function destroyAdmin(User $user): RedirectResponse
    {
        if (! $user->isAdmin()) {
            return back()->with('error', 'Pengguna yang dipilih bukan administrator.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if (User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus satu-satunya administrator yang tersisa di sistem.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun petugas '{$name}' telah berhasil dihapus dari sistem.");
    }
}
