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

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Mahasiswa');
        $response->assertSee('Nomor Induk Mahasiswa (NIM)');
        $response->assertDontSee('Petugas / Admin');
    }

    public function test_new_student_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fadilah Mahasiswa',
            'nim_nip' => '220109999',
            'email' => 'fadilah@mahasiswa.ac.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'fadilah@mahasiswa.ac.id',
            'nim_nip' => '220109999',
            'role' => 'student',
        ]);
        $response->assertRedirect(route('catalog.index'));
    }

    public function test_public_registration_always_creates_student_account(): void
    {
        // Even if role='admin' is attempted in payload, public register forces student
        $response = $this->post('/register', [
            'name' => 'Attempted Admin',
            'role' => 'admin',
            'nim_nip' => '220108888',
            'email' => 'attempt@mahasiswa.ac.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'attempt@mahasiswa.ac.id',
            'nim_nip' => '220108888',
            'role' => 'student',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'attempt@mahasiswa.ac.id',
            'role' => 'admin',
        ]);
        $response->assertRedirect(route('catalog.index'));
    }

    public function test_admin_can_access_petugas_management_and_register_new_admin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.admins'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Petugas');

        $storeResponse = $this->actingAs($admin)->post(route('admin.admins.store'), [
            'name' => 'Petugas Baru Laboratorium',
            'nim_nip' => '199505052023011002',
            'email' => 'petugas.baru@kampus.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'petugas.baru@kampus.ac.id',
            'nim_nip' => '199505052023011002',
            'role' => 'admin',
        ]);
    }

    public function test_students_cannot_access_petugas_management(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->actingAs($student)->get(route('admin.admins'));
        $response->assertRedirect(route('catalog.index'));

        $postResponse = $this->actingAs($student)->post(route('admin.admins.store'), [
            'name' => 'Hacker Admin',
            'nim_nip' => '999999',
            'email' => 'hacker@kampus.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $postResponse->assertRedirect(route('catalog.index'));
        $this->assertDatabaseMissing('users', ['email' => 'hacker@kampus.ac.id']);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.admins.destroy', $admin));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Testing User',
            'role' => 'student',
            'nim_nip' => '11223344',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@kampus.ac.id',
        ]);

        $response = $this->post('/register', [
            'name' => 'Duplicate Email',
            'role' => 'student',
            'nim_nip' => '99887766',
            'email' => 'existing@kampus.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_rejects_duplicate_nim_nip(): void
    {
        User::factory()->create([
            'nim_nip' => '99887766',
        ]);

        $response = $this->post('/register', [
            'name' => 'Duplicate NIM',
            'role' => 'student',
            'nim_nip' => '99887766',
            'email' => 'unique@kampus.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('nim_nip');
        $this->assertGuest();
    }
}
