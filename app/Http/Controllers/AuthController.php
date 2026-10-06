<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the application login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('catalog.index');
        }

        return view('auth.login');
    }

    /**
     * Show the application registration form.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('catalog.index');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming student registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim_nip' => ['required', 'string', 'max:50', 'unique:users,nim_nip'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nim_nip.required' => 'Nomor Induk Mahasiswa (NIM) wajib diisi.',
            'nim_nip.unique' => 'NIM ini sudah terdaftar dalam sistem.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar dalam sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus :min karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'nim_nip' => trim($validated['nim_nip']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('catalog.index')
            ->with('success', 'Pendaftaran akun berhasil! Selamat datang di PinjamKampus, '.$user->name.'.');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau NIM/NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nim_nip';

        $attemptCredentials = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($attemptCredentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                // Clear any non-admin intended URL (e.g. from visiting /katalog before login)
                $intended = session()->pull('url.intended');
                if (! $intended || ! str_contains($intended, '/admin')) {
                    $intended = route('admin.dashboard');
                }

                return redirect()->to($intended)
                    ->with('success', 'Selamat datang, Administrator '.$user->name.'!');
            }

            return redirect()->intended(route('catalog.index'))
                ->with('success', 'Selamat datang kembali, '.$user->name.'!');
        }

        return back()->withErrors([
            'login' => 'Email / NIM / NIP atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('login');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
