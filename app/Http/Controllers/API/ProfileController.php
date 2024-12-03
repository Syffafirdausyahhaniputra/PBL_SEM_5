<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the authenticated dosen's profile.
     */
    public function index()
    {
        try {
            // Ambil data dosen dengan relasi user, bidang, matkul

            $user = UserModel::with([])
                ->where('user_id', Auth::id())
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data User tidak ditemukan.'
                ], 404);
            }

            // Ambil data role melalui relasi User
            $role = $user->role;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->user_id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'nip' => $user->nip,
                    'role' => $role->role_nama,  // Menambahkan role
                    'avatar' => $user->avatar ? asset('avatars/' . $user->user->avatar) : asset('avatars/user.jpg')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated dosen's profile.
     */
    public function update(Request $request)
    {
        try {
            $user = UserModel::where('user_id', Auth::id())->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Pimpinan tidak ditemukan.'
                ], 404);
            }

            // Validasi input
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $user->user_id . ',user_id',
                'nama'     => 'required|string|max:100',
                'nip'      => 'required|string|max:50',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Cek perubahan data
            $isChanged = false;

            if (
                $user->username != $request->username ||
                $user->nama != $request->nama ||
                $user->nip != $request->nip ||
                $request->hasFile('avatar') ||
                $request->filled('old_password')

            ) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan pada profil Anda.'
                ]);
            }

            // Update data user
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->nip = $request->nip;


            // Handle password update
            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['old_password' => ['Password lama salah']]
                    ]);
                }
                $user->password = Hash::make($request->password);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    $oldAvatarPath = public_path('avatars/' . $user->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                $file = $request->file('avatar');
                $fileName = time() . '_' . preg_replace('/\\s+/', '', $file->getClientOriginalName());
                $file->move(public_path('avatars'), $fileName);
                $user->avatar = $fileName;
            }

            // Simpan perubahan
            $user->save();


            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data' => [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'nip' => $user->nip,
                    'avatar' => $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/user.jpg')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    
}
