<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);


        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL)
                      ? 'email'
                      : 'name';

        $credentials = [
            $loginField => $request->login,
            'password'  => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'login' => 'Name/email atau password salah.',
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('karyawan.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}