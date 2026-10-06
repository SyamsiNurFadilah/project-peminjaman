<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds for initial administrator account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name' => 'Administrator Inventaris',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nim_nip' => '198503152010121002',
            ]
        );
    }
}
