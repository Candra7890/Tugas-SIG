<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->username, 'email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Username, Email atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}