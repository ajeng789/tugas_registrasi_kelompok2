<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan Form Registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Memproses Data Registrasi & Otomatis Login
    public function register(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Simpan Akun ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Otomatis Login Setelah Registrasi
        Auth::login($user);

        // 4. Redirect ke Dashboard
        return redirect()->intended('/dashboard');
    }

    // Halaman Dashboard
    public function dashboard()
    {
        return view('dashboard');
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/register');
    }
}