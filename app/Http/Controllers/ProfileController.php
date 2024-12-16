<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());

        $breadcrumb = (object) [
            'title' => 'Profile',
            'subtitle'  => 'Bio data diri pengguna'
        ];

        $activeMenu = 'profile';

        $user = Auth::user();

        return view('profile.index', compact('user'), [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'email'     => 'required|string|max:100',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = UserModel::find($id);

            // Cek perubahan data
            $isChanged = false;

            if (
                $user->username != $request->username ||
                $user->email != $request->email ||
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
            $user->email = $request->email;

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
                // Validasi file upload
                $request->validate([
                    'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Hapus avatar lama jika ada
                if ($user->avatar) {
                    $oldAvatarPath = public_path('avatars/' . $user->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                // Simpan avatar baru di folder 'avatars' pada direktori public
                $file = $request->file('avatar');
                $fileName = time() . '' . preg_replace('/\\s+/', '', $file->getClientOriginalName());
                $file->move(public_path('avatars'), $fileName);

                // Perbarui avatar di database
                $user->avatar = $fileName;
            }

            // Simpan perubahan pada user
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'user' => [
                    'email' => $user->email,
                    'username' => $user->username,
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
