<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            // Ambil role_id dari pengguna yang sedang login
            $roleId = Auth::user()->role_id;

            // Redirect berdasarkan role_id
            if ($roleId == 1 || $roleId == 2) {
                return redirect('/welcome');
            } elseif ($roleId == 3) {
                return redirect('/welcome2');
            }
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            // Tentukan apakah input adalah email atau username
            $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Gunakan field dinamis untuk autentikasi
            $attempt = Auth::attempt([
                $loginField => $credentials['username'],
                'password' => $credentials['password'],
            ]);

            if ($attempt) {
                $user = Auth::user();

                session(['role_id' => $user->role_id]);

                $redirectUrl = match ($user->role_id) {
                    1, 2 => '/welcome',
                    3 => '/welcome2',
                    default => '/default',
                };

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url($redirectUrl)
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('role_id'); // Hapus role_id dari session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
