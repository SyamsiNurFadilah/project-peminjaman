<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Sistem');
    }

    public function test_users_can_authenticate_using_email(): void
    {
        $user = User::factory()->create([
            'email' => 'mahasiswa@kampus.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'mahasiswa@kampus.ac.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/katalog');
    }

    public function test_users_can_authenticate_using_nim_nip(): void
    {
        $user = User::factory()->create([
            'nim_nip' => '220101999',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => '220101999',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/katalog');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'mahasiswa@kampus.ac.id',
            'password' => bcrypt('correct-password'),
        ]);

        $this->post('/login', [
            'login' => 'mahasiswa@kampus.ac.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_admin_dashboard_even_if_visited_catalog_first(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@kampus.ac.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        // Simulate visiting catalog first which might set url.intended
        $this->withSession(['url.intended' => route('catalog.index')]);

        $response = $this->post('/login', [
            'login' => 'admin@kampus.ac.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_admin_visiting_root_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
