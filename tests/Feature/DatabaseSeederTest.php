<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that database seeder populates users, items, and borrowings properly.
     */
    public function test_database_seeder_populates_required_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Verify Admin User
        $admin = User::where('email', 'admin@kampus.ac.id')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->isAdmin());
        $this->assertEquals('198503152010121002', $admin->nim_nip);

        // Verify Student Users
        $student = User::where('email', 'samsi@mahasiswa.ac.id')->first();
        $this->assertNotNull($student);
        $this->assertTrue($student->isStudent());
        $this->assertEquals('220101001', $student->nim_nip);

        // Verify Inventory Items
        $this->assertDatabaseHas('items', [
            'item_code' => 'INV-LIB-042',
            'category' => 'Perpustakaan',
            'status' => 'available',
        ]);

        $this->assertDatabaseHas('items', [
            'item_code' => 'INV-LAB-001',
            'category' => 'Lab',
        ]);

        // Verify Borrowing Relations
        $borrowing = Borrowing::first();
        $this->assertNotNull($borrowing);
        $this->assertInstanceOf(User::class, $borrowing->user);
        $this->assertInstanceOf(Item::class, $borrowing->item);
    }
}
