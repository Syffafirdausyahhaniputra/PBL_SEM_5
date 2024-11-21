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

        if (Auth::attempt($credentials)) {
            // Ambil data user yang sudah login
            $user = Auth::user();

            // Simpan role_id ke dalam session
            session(['role_id' => $user->role_id]);

            // Tentukan URL pengalihan berdasarkan role_id
            if ($user->role_id == 1 || $user->role_id == 2) {
                $redirectUrl = '/welcome'; // Redirect untuk role_id 1 dan 2
            } elseif ($user->role_id == 3) {
                $redirectUrl = '/welcome2'; // Redirect untuk role_id 3
            } else {
                // Jika ada role_id lain, Anda bisa mengatur pengalihan default atau menangani sesuai kebutuhan
                $redirectUrl = '/default'; // Contoh pengalihan default
            }

            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => url($redirectUrl) // Gunakan URL yang ditentukan
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

    public function register()
    {
        $role = RoleModel::select('role_id', 'role_nama')->get();

        return view('auth.register')->with('role', $role);
    }

    public function postRegister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'role_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'nip' => 'required|string|max:50',
                'password' => 'required|min:5'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Hash password sebelum disimpan
            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            // Simpan data user
            UserModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
                'redirect' => url('login') // Redirect ke halaman login
            ]);
        }

        // Jika bukan AJAX, arahkan ke halaman login
        return redirect('login')->with('success', 'Registrasi berhasil!');
    }
}
