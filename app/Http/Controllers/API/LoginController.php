<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Ambil kredensial
        $credentials = $request->only('username', 'password');

        // Cek autentikasi
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password Anda salah'
            ], 401);
        }

        // Ambil data pengguna
        $user = auth()->guard('api')->user();
        $roleId = $user->role_id; // Mengambil role_id dari UserModel
        $userId = $user->user_id; // Mengambil user_id dari UserModel

        // Respons jika login berhasil
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $userId, // Menampilkan user_id
                'username' => $user->username,
                'role_id' => $roleId
            ],
            'token' => $token
        ], 200);
    }
}
