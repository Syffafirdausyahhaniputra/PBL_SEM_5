<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());
        return $user;

        $breadcrumb = (object) [
            'title' => 'Profile',
            'subtitle'  => 'Bio data diri pengguna'
        ];

        // $activeMenu = 'profile';

        // return view('profile.index', compact('user'), [
        //     'breadcrumb' => $breadcrumb, 
        //     'activeMenu' => $activeMenu
        // ]);
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        return $user;
        $breadcrumb = (object) ['title' => 'Detail User', 'list' => ['Home', 'User', 'Detail']];
        $page = (object) ['title' => 'Detail user'];
        $activeMenu = 'user'; // set menu yang sedang aktif
        // return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama'     => 'required|string|max:100',
                'nip'      => 'required|string|max:50',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = UserModel::find($id);

            // Cek perubahan data
            $isChanged = false;

            if ($user->username != $request->username || 
                $user->nama != $request->nama || 
                $user->nip != $request->nip || 
                $request->hasFile('avatar') ||
                $request->filled('old_password')) {
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
                if (!Storage::exists('public/avatar')) {
                    Storage::makeDirectory('public/avatar');
                }

                // Delete old avatar
                if ($user->avatar) {
                    $oldAvatarPath = storage_path('app/public/avatar/' . $user->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }

                // Store new avatar
                $file = $request->file('avatar');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/avatar', $fileName);
                $user->avatar = $fileName;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'user' => [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'avatar' => $user->avatar ? asset('storage/avatar/' . $user->avatar) : null
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