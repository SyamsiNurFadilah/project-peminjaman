<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

// Root route: Redirect admin to admin dashboard, student/guest to catalog
Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('catalog.index');
})->name('home');

// Catalog (Accessible to all users)
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{item}', [CatalogController::class, 'show'])->name('catalog.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes for Authenticated Users (Students & General)
Route::middleware('auth')->group(function () {
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::get('/api/items/{code}', [ScanController::class, 'checkItem'])->name('api.items.check');

    Route::post('/peminjaman', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/peminjaman-saya', [BorrowingController::class, 'myBorrowings'])->name('borrowings.my');
});

// Dedicated Admin Panel Routes (Protected by auth & admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Borrowings Verification & Management
    Route::get('/peminjaman', [AdminController::class, 'borrowings'])->name('borrowings');
    Route::post('/peminjaman/{borrowing}/approve', [AdminController::class, 'approveBorrowing'])->name('borrowings.approve');
    Route::post('/peminjaman/{borrowing}/reject', [AdminController::class, 'rejectBorrowing'])->name('borrowings.reject');
    Route::post('/peminjaman/{borrowing}/return', [AdminController::class, 'returnBorrowing'])->name('borrowings.return');

    // Inventory Management & QR Code Generator
    Route::get('/barang', [AdminController::class, 'items'])->name('items');
    Route::post('/barang', [AdminController::class, 'storeItem'])->name('items.store');
    Route::put('/barang/{item}', [AdminController::class, 'updateItem'])->name('items.update');
    Route::delete('/barang/{item}', [AdminController::class, 'destroyItem'])->name('items.destroy');
    Route::get('/barang/{item}/cetak-qr', [AdminController::class, 'printQr'])->name('items.print_qr');

    // Petugas & Administrator Management
    Route::get('/petugas', [AdminController::class, 'admins'])->name('admins');
    Route::post('/petugas', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/petugas/{user}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');
});
