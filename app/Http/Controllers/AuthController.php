<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);
        $login = $credentials['login'];

        $attempts = [
            ['email' => $login, 'password' => $credentials['password'], 'is_active' => true],
            ['username' => $login, 'password' => $credentials['password'], 'is_active' => true],
        ];

        foreach ($attempts as $attempt) {
            if (Auth::attempt($attempt, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
        }

        throw ValidationException::withMessages([
            'login' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
