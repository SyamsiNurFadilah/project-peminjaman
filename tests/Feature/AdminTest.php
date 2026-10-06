<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get('/admin');

        $response->assertRedirect('/katalog');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Manajemen Inventaris');
    }

    public function test_admin_can_view_borrowings_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $item = Item::factory()->create();

        Borrowing::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(3),
            'status' => 'pending',
            'notes' => 'Tugas kuliah',
        ]);

        $response = $this->actingAs($admin)->get('/admin/peminjaman');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Peminjaman');
        $response->assertSee($student->name);
        $response->assertSee($item->name);
    }

    public function test_admin_can_approve_pending_borrowing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $item = Item::factory()->create(['available_stock' => 5]);

        $borrowing = Borrowing::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/peminjaman/{$borrowing->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'borrowed',
        ]);
    }

    public function test_admin_can_reject_pending_borrowing_and_restore_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $item = Item::factory()->create(['total_stock' => 5, 'available_stock' => 4, 'status' => 'available']);

        $borrowing = Borrowing::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/peminjaman/{$borrowing->id}/reject");

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'rejected',
        ]);

        $item->refresh();
        $this->assertEquals(5, $item->available_stock);
    }

    public function test_admin_can_confirm_return_and_restore_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $item = Item::factory()->create(['total_stock' => 2, 'available_stock' => 1, 'status' => 'available']);

        $borrowing = Borrowing::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'borrow_date' => now()->subDays(2),
            'due_date' => now()->addDays(1),
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($admin)->post("/admin/peminjaman/{$borrowing->id}/return");

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'returned',
        ]);

        $item->refresh();
        $this->assertEquals(2, $item->available_stock);
    }

    public function test_admin_can_view_items_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::factory()->create(['name' => 'Kamera DSLR Canon']);

        $response = $this->actingAs($admin)->get('/admin/barang');

        $response->assertStatus(200);
        $response->assertSee('Kelola Inventaris');
        $response->assertSee('Kamera DSLR Canon');
    }

    public function test_admin_can_create_item_with_auto_generated_qr_code(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/barang', [
            'name' => 'Mikroskop Biologi Optik',
            'category' => 'Lab',
            'total_stock' => 8,
            'status' => 'available',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', [
            'name' => 'Mikroskop Biologi Optik',
            'category' => 'Lab',
            'total_stock' => 8,
            'available_stock' => 8,
            'status' => 'available',
        ]);

        $item = Item::where('name', 'Mikroskop Biologi Optik')->first();
        $this->assertNotNull($item->item_code);
        $this->assertStringStartsWith('INV-LAB-', $item->item_code);
    }

    public function test_admin_can_update_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::factory()->create([
            'name' => 'Old Name',
            'total_stock' => 5,
            'available_stock' => 5,
            'category' => 'Lab',
            'status' => 'available',
        ]);

        $response = $this->actingAs($admin)->put("/admin/barang/{$item->id}", [
            'name' => 'New Updated Name',
            'category' => 'Lab',
            'total_stock' => 6,
            'available_stock' => 6,
            'status' => 'available',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'New Updated Name',
            'total_stock' => 6,
        ]);
    }

    public function test_admin_can_delete_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/barang/{$item->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }

    public function test_admin_can_view_print_qr_sticker(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::factory()->create(['item_code' => 'INV-TEST-001', 'name' => 'Barang Uji Coba']);

        $response = $this->actingAs($admin)->get("/admin/barang/{$item->id}/cetak-qr");

        $response->assertStatus(200);
        $response->assertSee('INV-TEST-001');
        $response->assertSee('Barang Uji Coba');
    }

    public function test_admin_cannot_access_scan_page_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/scan');

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_admin_viewing_catalog_does_not_see_scan_button(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Item::factory()->create(['name' => 'Proyektor Ruang Seminar', 'status' => 'available', 'available_stock' => 5]);

        $response = $this->actingAs($admin)->get('/katalog');

        $response->assertStatus(200);
        $response->assertDontSee('Buka Scan Mandiri');
        $response->assertDontSee('Pinjam via Scan QR');
        $response->assertSee('Lihat Detail Barang');
    }

    public function test_admin_viewing_item_detail_does_not_see_scan_loan_button(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::factory()->create(['name' => 'Kamera DSLR Lab', 'status' => 'available', 'available_stock' => 3]);

        $response = $this->actingAs($admin)->get("/katalog/{$item->id}");

        $response->assertStatus(200);
        $response->assertDontSee('Pinjam Barang Ini Sekarang');
        $response->assertSee('Kelola Barang di Panel Admin');
    }
}
