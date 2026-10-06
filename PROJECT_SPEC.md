# AGENTS.md - Sistem Peminjaman Mandiri Kampus (Laravel + Vite + Tailwind)

Dokumen ini adalah cetak biru teknis dan standar desain UI/UX bagi AI Agent (Google Antigravity) untuk membangun aplikasi web peminjaman inventaris kampus menggunakan **Laravel**, **Vite (Frontend Asset Bundler)**, dan antarmuka yang natural, bersih, serta elegan.

---

## 1. Stack Teknologi & Arsitektur
* **Backend:** Laravel (MVC, Eloquent ORM, Blade Templates / Laravel Controllers).
* **Frontend Asset Builder:** Laravel Vite (`vite.config.js`) untuk kompilasi CSS/JS yang cepat.
* **Styling:** Tailwind CSS dengan tambahan komponen kustom agar *look and feel*-nya natural (tidak terlihat seperti template AI pasaran).
* **Database:** MySQL / PostgreSQL.
* **Library Pendukung:** `html5-qrcode` atau `ZXing` (dimuat via CDN/NPM di Vite) untuk pemindaian QR Code lewat kamera perangkat.

---

## 2. Standar Desain UI/UX (Natural & Human-Made)
Hindari desain generik khas AI (seperti penggunaan warna gradasi ungu/pink berlebihan, shadow neon, atau sudut tombol yang terlalu melengkung/bulat). Ikuti prinsip desain natural berikut:
* **Tipografi & Spasi:** Gunakan font standar modern (Inter / Plus Jakarta Sans) dengan *line-height* yang longgar agar mudah dibaca. Jarak antar elemen proporsional (menggunakan padding/margin Tailwind yang konsisten).
* **Skema Warna:** 
  * *Neutral/Background:* Bersih dengan nuansa abu-abu terang natural (`bg-slate-50` atau `bg-zinc-50`), bukan putih bersih mutlak.
  * *Primary Color:* Gunakan satu warna utama yang profesional, misal **Slate-900** untuk teks utama dan **Blue-600 / Indigo-600** untuk tombol aksi utama secukupnya.
  * *Status Badge:* Desain badge yang lembut, tipis, dan tidak mencolok (misalnya hijau pudar untuk status tersedia, abu-abu gelap untuk dipinjam).
* **Interaksi & Feedback:** Gunakan transisi halus (`transition duration-150 ease-in-out`) pada hover tombol dan kartu, hindari animasi yang berlebihan.

---

## 3. Database Schema Design (Laravel Migrations)

### A. Tabel `users`
* `id` (Primary Key)
* `name` (VARCHAR)
* `email` (VARCHAR, Unique)
* `password` (VARCHAR)
* `role` (ENUM: 'student', 'admin')
* `nim_nip` (VARCHAR, Unique)
* `timestamps()`

### B. Tabel `items` (Inventaris & Kode QR)
* `id` (Primary Key)
* `item_code` (VARCHAR, Unique - Kode string yang akan di-generate menjadi QR Code, misal: `INV-LIB-042`)
* `name` (VARCHAR - Nama barang)
* `category` (VARCHAR - Lab, Perpustakaan, Alat Himpunan)
* `total_stock` (INT)
* `available_stock` (INT)
* `status` (ENUM: 'available', 'empty', 'maintenance')
* `timestamps()`

### C. Tabel `borrowings` (Transaksi)
* `id` (Primary Key)
* `user_id` (Foreign Key ke `users`)
* `item_id` (Foreign Key ke `items`)
* `borrow_date` (DATETIME)
* `due_date` (DATETIME)
* `return_date` (DATETIME, Nullable)
* `status` (ENUM: 'pending', 'borrowed', 'returned', 'rejected')
* `notes` (TEXT, Nullable)
* `timestamps()`

---

## 4. Alur Fitur Utama & Modul

### A. Sisi Mahasiswa (User)
1. **Katalog Barang:** Halaman daftar inventaris dengan pencarian real-time dan filter kategori.
2. **Scan Mandiri (QR Code):** 
   * Mahasiswa membuka halaman pinjam, lalu mengaktifkan kamera web via script Vite.
   * Kamera memindai QR Code pada barang fisik.
   * Sistem mendeteksi `item_code`, melakukan pencocokan instan, dan mengisi form peminjaman secara otomatis (*auto-fill*).
3. **Riwayat Peminjaman:** Tabel status peminjaman aktif dan tenggat waktu pengembalian.

### B. Sisi Admin (Petugas)
1. **Dashboard Manajemen:** Statistik ringkas jumlah peminjaman dan inventaris.
2. **Kelola Barang & QR Code Generator:** Admin menambah barang baru dan sistem otomatis menyediakan cetakan QR Code berdasarkan `item_code`.
3. **Verifikasi Transaksi:** Menyetujui permintaan pinjam (*pending* -> *borrowed*) dan mengonfirmasi pengembalian barang (*returned*).

---

## 5. Instruksi Kerja untuk AI Agent (Antigravity)
1. **Gunakan Perintah Laravel:** Saat membuat route, controller, model, dan migration, gunakan struktur standar Laravel (misal: `php artisan make:model Item -mcr`).
2. **Integrasi Vite:** Pastikan semua aset styling (Tailwind) di-compile dengan benar melalui `@vite(['resources/css/app.css', 'resources/js/app.js'])` di layout utama Blade.
3. **Clean Code & Bahasa Indonesia:** Tulis kode yang modular, gunakan bahasa Indonesia untuk seluruh teks antarmuka (*UI copy*), tombol, dan pesan validasi form.