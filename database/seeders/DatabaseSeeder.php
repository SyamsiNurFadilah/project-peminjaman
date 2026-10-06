<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin / Petugas Inventaris
        $this->call(AdminSeeder::class);
        $admin = User::where('email', 'admin@kampus.ac.id')->first();

        // 2. Akun Mahasiswa
        $mahasiswa1 = User::firstOrCreate(
            ['email' => 'samsi@mahasiswa.ac.id'],
            [
                'name' => 'Samsi Nurfadilah',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nim_nip' => '220101001',
            ]
        );

        $mahasiswa2 = User::firstOrCreate(
            ['email' => 'budi@mahasiswa.ac.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nim_nip' => '220101002',
            ]
        );

        $mahasiswa3 = User::firstOrCreate(
            ['email' => 'siti@mahasiswa.ac.id'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nim_nip' => '220101003',
            ]
        );

        // 3. Data Barang Inventaris (dengan Kode QR unik)
        $itemsData = [
            [
                'item_code' => 'INV-LIB-042',
                'name' => 'Buku Panduan Algoritma & Pemrograman',
                'category' => 'Perpustakaan',
                'total_stock' => 10,
                'available_stock' => 9,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-LIB-088',
                'name' => 'Kamus Rekayasa Perangkat Lunak Modern',
                'category' => 'Perpustakaan',
                'total_stock' => 4,
                'available_stock' => 0,
                'status' => 'empty',
            ],
            [
                'item_code' => 'INV-LIB-103',
                'name' => 'Modul Praktikum Jaringan Komputer',
                'category' => 'Perpustakaan',
                'total_stock' => 15,
                'available_stock' => 15,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-LAB-001',
                'name' => 'Proyektor Epson EB-X500 HDMI',
                'category' => 'Lab',
                'total_stock' => 6,
                'available_stock' => 5,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-LAB-002',
                'name' => 'Kamera DSLR Canon EOS 3000D Kit',
                'category' => 'Lab',
                'total_stock' => 3,
                'available_stock' => 3,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-LAB-003',
                'name' => 'Osiloskop Digital Rigol DS1054Z',
                'category' => 'Lab',
                'total_stock' => 4,
                'available_stock' => 4,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-LAB-009',
                'name' => 'Mikroskop Digital Lab Komputer/Sains',
                'category' => 'Lab',
                'total_stock' => 2,
                'available_stock' => 0,
                'status' => 'maintenance',
            ],
            [
                'item_code' => 'INV-HMP-010',
                'name' => 'Sound System Portable Wireless + 2 Mic',
                'category' => 'Alat Himpunan',
                'total_stock' => 2,
                'available_stock' => 1,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-HMP-015',
                'name' => 'Megaphone TOA ZR-2015W',
                'category' => 'Alat Himpunan',
                'total_stock' => 3,
                'available_stock' => 3,
                'status' => 'available',
            ],
            [
                'item_code' => 'INV-HMP-021',
                'name' => 'Kabel Roll Listrik 50 Meter Heavy Duty',
                'category' => 'Alat Himpunan',
                'total_stock' => 5,
                'available_stock' => 5,
                'status' => 'available',
            ],
        ];

        $items = [];
        foreach ($itemsData as $data) {
            $items[$data['item_code']] = Item::firstOrCreate(
                ['item_code' => $data['item_code']],
                $data
            );
        }

        // 4. Data Contoh Riwayat Peminjaman (Borrowings)
        Borrowing::firstOrCreate(
            [
                'user_id' => $mahasiswa1->id,
                'item_id' => $items['INV-LAB-001']->id,
                'status' => 'borrowed',
            ],
            [
                'borrow_date' => now()->subDays(2),
                'due_date' => now()->addDays(5),
                'return_date' => null,
                'notes' => 'Dipinjam untuk presentasi seminar tugas akhir di Lab Komputer 3.',
            ]
        );

        Borrowing::firstOrCreate(
            [
                'user_id' => $mahasiswa2->id,
                'item_id' => $items['INV-LIB-042']->id,
                'status' => 'returned',
            ],
            [
                'borrow_date' => now()->subDays(7),
                'due_date' => now()->subDays(1),
                'return_date' => now()->subDays(1),
                'notes' => 'Telah dikembalikan tepat waktu dalam kondisi bersih dan lengkap.',
            ]
        );

        Borrowing::firstOrCreate(
            [
                'user_id' => $mahasiswa3->id,
                'item_id' => $items['INV-HMP-010']->id,
                'status' => 'pending',
            ],
            [
                'borrow_date' => now(),
                'due_date' => now()->addDays(3),
                'return_date' => null,
                'notes' => 'Pengajuan peminjaman sound system untuk gladi bersih workshop mahasiswa.',
            ]
        );
    }
}
