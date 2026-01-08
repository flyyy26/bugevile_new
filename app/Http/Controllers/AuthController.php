<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // LOGIN KARYAWAN
        if ($request->username === 'karyawan') {

            if ($request->password !== 'karyawan123') {
                return back()->withErrors([
                    'password' => 'Password karyawan salah'
                ]);
            }

            Auth::loginUsingId(999); // ID user karyawan
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        // LOGIN ADMIN
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/dashboard/login');
    }
}