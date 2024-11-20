<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        // $userId = session('user_id');

        $user = UserModel::select(
            'user_id', 'm_user.role_id', 'username', 'nama', 'nip'
        )->join('m_role', '=', 'm_role.role_id')->get();
        return $user;
        // if (!$userId) {
        //     return response()->json([
        //         'status' => 'error', 
        //         'message' => 'User not authenticated'
        //     ], 401);
        // }

        // try {
        //     $user = UserModel::with('role')
        //         ->select('user_id', 'username', 'nama', 'nip', 'avatar', 'role_id')
        //         ->findOrFail($userId);

        //     // Format response sesuai kebutuhan frontend
        //     $responseData = [
        //         'nama' => $user->nama,
        //         'nip' => $user->nip,
        //         'role' => $user->role->role_nama ?? null,
        //         'avatar' => $user->avatar
        //     ];

        //     return response()->json([
        //         'status' => 'success',
        //         'data' => $responseData
        //     ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to fetch user data'
        //     ], 500);
        // }
    }

    public function show(string $id)
    {
        try {
            $user = UserModel::with('role')
                ->select('user_id', 'username', 'nama', 'nip', 'avatar', 'role_id')
                ->findOrFail($id);

            $responseData = [
                'nama' => $user->nama,
                'nip' => $user->nip,
                'role' => $user->role->role_nama ?? null,
                'avatar' => $user->avatar
            ];

            return response()->json([
                'status' => 'success',
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }
}