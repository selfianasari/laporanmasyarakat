<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cek kredensial
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return $user->is_admin ? redirect()->route('admin.dashboard') : redirect()->route('user.profile.form');
        }

        // Jika kredensial salah
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok.',
        ]);
    }

    // Memproses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed', // Pastikan form memiliki password_confirmation
        ]);

        // Buat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Otomatis login setelah registrasi berhasil
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('user.profile.form')->with('success', 'Registrasi berhasil dan Anda sudah login!');
        }

        return back()->withErrors(['register' => 'Registrasi berhasil, tapi login otomatis gagal.']);
    }

    // Memproses logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
