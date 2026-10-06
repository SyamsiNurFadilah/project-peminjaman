<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanAndBorrowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_scan_page(): void
    {
        $response = $this->get('/scan');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_student_can_view_scan_page(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/scan');

        $response->assertStatus(200);
        $response->assertSee('Scan Mandiri');
        $response->assertSee($student->name);
    }

    public function test_api_check_item_returns_valid_item_data(): void
    {
        $student = User::factory()->student()->create();
        $item = Item::factory()->create([
            'item_code' => 'INV-LIB-042',
            'name' => 'Buku Panduan Algoritma',
            'available_stock' => 5,
            'status' => 'available',
        ]);

        $response = $this->actingAs($student)->getJson('/api/items/INV-LIB-042');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'item' => [
                'item_code' => 'INV-LIB-042',
                'name' => 'Buku Panduan Algoritma',
                'is_available' => true,
            ],
        ]);
    }

    public function test_api_check_item_returns_404_for_unknown_code(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->getJson('/api/items/KODE-TIDAK-ADA');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_student_can_submit_borrowing_request_and_stock_decrements(): void
    {
        $student = User::factory()->student()->create();
        $item = Item::factory()->create([
            'item_code' => 'INV-LAB-001',
            'total_stock' => 3,
            'available_stock' => 3,
            'status' => 'available',
        ]);

        $response = $this->actingAs($student)->post('/peminjaman', [
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
            'duration_days' => 7,
            'notes' => 'Peminjaman untuk keperluan praktikum.',
        ]);

        $response->assertRedirect('/peminjaman-saya');

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $student->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'notes' => 'Peminjaman untuk keperluan praktikum.',
        ]);

        // Stock must be decremented by 1
        $this->assertEquals(2, $item->fresh()->available_stock);
    }

    public function test_student_cannot_borrow_item_with_zero_stock(): void
    {
        $student = User::factory()->student()->create();
        $item = Item::factory()->empty()->create([
            'available_stock' => 0,
            'status' => 'empty',
        ]);

        $response = $this->actingAs($student)->post('/peminjaman', [
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
            'duration_days' => 7,
        ]);

        $response->assertSessionHasErrors('item_id');
        $this->assertEquals(0, Borrowing::count());
    }

    public function test_student_can_view_my_borrowings_history(): void
    {
        $student = User::factory()->student()->create();
        $borrowing = Borrowing::factory()->create([
            'user_id' => $student->id,
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($student)->get('/peminjaman-saya');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Peminjaman Saya');
        $response->assertSee($borrowing->item->name);
    }
}
