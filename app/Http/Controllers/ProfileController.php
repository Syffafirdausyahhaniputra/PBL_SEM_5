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

        return view('profile.index', compact('user'), [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama'     => 'required|string|max:100',
                'nip'      => 'required|string|max:50',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = UserModel::find($id);

            if (
                $user->username === $request->username &&
                $user->nama === $request->nama &&
                $user->nip === $request->nip &&
                !$request->hasFile('avatar') &&
                !$request->filled('old_password')
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan pada profil Anda.'
                ], 400);
            }

            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->nip = $request->nip;

            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'old_password' => 'Password lama salah'
                        ]
                    ], 422);
                }
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                Storage::ensureDirectoryExists('public/avatar');

                if ($user->avatar && Storage::exists('public/avatar/' . $user->avatar)) {
                    Storage::delete('public/avatar/' . $user->avatar);
                }

                $file = $request->file('avatar');
                $fileName = time() . '_' . $file->hashName();
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
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }
}
