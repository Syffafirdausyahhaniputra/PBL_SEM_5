<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            
        log::info('Request data:', $request->all());
                    $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama'     => 'required|string|max:100',
            'nip'      => 'required|string|max:50',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:5',
,
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'        ]);

        // Ambil data user
        $user = UserModel::findOrFail($id);

        // Update data user
        if ($request->filled('username')) $user->username = $request->username;
        if ($request->filled('nama')) $user->nama = $request->nama;
        if ($request->filled('nip')) $user->nip = $request->nip;

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

        // Handle avatar update
        if ($request->filled('avatar')) {
            $user->avatar = $request->avatar;
        }

        // Simpan perubahan
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user' => [
                'nama' => $user->nama,
                'username' => $user->username,
                'avatar' => $user->avatar,
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

}\Log::info('Request data:', $request->all());
