<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_screen_can_be_rendered(): void
    {
        Item::factory()->create([
            'name' => 'Proyektor Epson EB-X500',
            'item_code' => 'INV-LAB-101',
            'category' => 'Lab',
        ]);

        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X500');
        $response->assertSee('INV-LAB-101');
    }

    public function test_catalog_can_be_searched_by_name(): void
    {
        Item::factory()->create(['name' => 'Kamera Canon 3000D', 'item_code' => 'INV-LAB-001']);
        Item::factory()->create(['name' => 'Buku Algoritma', 'item_code' => 'INV-LIB-001']);

        $response = $this->get('/katalog?q=Canon');

        $response->assertStatus(200);
        $response->assertSee('Kamera Canon 3000D');
        $response->assertDontSee('Buku Algoritma');
    }

    public function test_catalog_can_be_filtered_by_category(): void
    {
        Item::factory()->create(['name' => 'Kamera Canon', 'category' => 'Lab']);
        Item::factory()->create(['name' => 'Megaphone TOA', 'category' => 'Alat Himpunan']);

        $response = $this->get('/katalog?category=Lab');

        $response->assertStatus(200);
        $response->assertSee('Kamera Canon');
        $response->assertDontSee('Megaphone TOA');
    }

    public function test_item_detail_screen_can_be_rendered(): void
    {
        $item = Item::factory()->create([
            'name' => 'Osiloskop Rigol',
            'item_code' => 'INV-LAB-050',
            'category' => 'Lab',
        ]);

        $response = $this->get("/katalog/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('Osiloskop Rigol');
        $response->assertSee('INV-LAB-050');
    }
}
