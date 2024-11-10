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
    // Validasi input termasuk field NIP dan avatar
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
        'nama'     => 'required|string|max:100',
        'nip'      => 'required|string|max:50',  // Tambahkan validasi untuk NIP
        'old_password' => 'nullable|string',
        'password' => 'nullable|min:5',
        'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif' // Validasi file gambar untuk avatar
    ]);

    $user = UserModel::find($id);

    // Cek apakah ada perubahan pada data
    $isChanged = false;

    // Periksa perubahan pada username, nama, atau nip
    if ($user->username != $request->username || $user->nama != $request->nama || $user->nip != $request->nip) {
        $isChanged = true;
    }

    // Periksa jika ada password lama dan cocok, maka ganti dengan password baru
    if ($request->filled('old_password') && Hash::check($request->old_password, $user->password)) {
        $isChanged = true;
    }

    // Jika ada file avatar yang diupload, anggap ada perubahan
    if ($request->hasFile('avatar')) {
        $isChanged = true;
    }

    // Jika tidak ada perubahan
    if (!$isChanged) {
        return redirect()->back()->with('info', 'Tidak ada perubahan pada profil Anda.');
    }

    // Update data pengguna
    $user->username = $request->username;
    $user->nama = $request->nama;
    $user->nip = $request->nip;  // Update NIP

    // Jika password lama benar dan password baru diisi, lakukan perubahan password
    if ($request->filled('old_password') && Hash::check($request->old_password, $user->password)) {
        $user->password = Hash::make($request->password);
    } elseif ($request->filled('old_password')) {
        return back()
            ->withErrors(['old_password' => 'Password lama salah'])
            ->withInput();
    }

    // Handle avatar update
    if ($request->hasFile('avatar')) {
        // Hapus avatar lama
        if ($user->avatar && Storage::exists('public/avatar/' . $user->avatar)) {
            Storage::delete('public/avatar/' . $user->avatar);
        }

        // Simpan avatar baru
        $file = $request->file('avatar');
        $fileName = $file->hashName();
        $file->storeAs('public/avatar', $fileName);
        $user->avatar = $fileName;
    }

    // Simpan perubahan pada user
    $user->save();

    // Redirect dengan pesan sukses
    return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
}
}