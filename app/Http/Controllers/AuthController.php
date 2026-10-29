<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isPelanggan()) {
                return redirect()->route('catalog.index');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Process login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isPelanggan()) {
                return redirect()->intended(route('catalog.index'))
                    ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang, ' . $user->name . ' (' . ucfirst($user->role) . ').');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Show registration form for customers.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('catalog.index');
        }
        return view('auth.register');
    }

    /**
     * Process customer registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_PELANGGAN,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Auto create customer profile
        Customer::create([
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'address' => $user->address,
        ]);

        Auth::login($user);

        return redirect()->route('catalog.index')
            ->with('success', 'Pendaftaran berhasil! Selamat berbelanja kamera di CameraStore.');
    }

    /**
     * Process logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
