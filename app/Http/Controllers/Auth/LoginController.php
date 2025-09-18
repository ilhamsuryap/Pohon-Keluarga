<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            if (!$user->is_approved) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui oleh admin. Silakan tunggu persetujuan.',
                ]);
            }

            if ($user->payment_status !== 'paid') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Silakan selesaikan pembayaran terlebih dahulu.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended($user->isAdmin() ? '/admin/dashboard' : '/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}