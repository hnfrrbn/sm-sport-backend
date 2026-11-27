<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer';

        $user = User::create($validated);

        ActivityLog::log($user, 'register', 'User', $user->id, 'User registered');

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Show the login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            ActivityLog::log($user, 'login', 'User', $user->id, 'User logged in');

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah. Silakan periksa kembali.',
        ]);
    }

    /**
     * Handle logout
     */
    public function demoLogin(Request $request, string $role): RedirectResponse
    {
        $allowedRoles = ['admin', 'customer'];

        if (! in_array($role, $allowedRoles, true)) {
            abort(404);
        }

        $user = User::where('role', $role)->first();

        if (! $user) {
            abort(404);
        }

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        ActivityLog::log($user, 'login', 'User', $user->id, 'Demo login via quick access');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log($user, 'logout', 'User', $user->id, 'User logged out');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari sistem.');
    }
}
