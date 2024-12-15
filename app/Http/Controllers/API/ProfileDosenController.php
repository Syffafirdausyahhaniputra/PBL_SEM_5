<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DosenBidangModel;
use App\Models\DosenMatkulModel;
use App\Models\DosenModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileDosenController extends Controller
{
    /**
     * Display the authenticated dosen's profile.
     */
    public function index()
    {
        try {
            // Ambil data dosen dengan relasi user, bidang, matkul
            $dosen = DosenModel::with([])
                ->where('user_id', Auth::id())
                ->first();

            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan.'
                ], 404);
            }

            $dosenBidang = DosenBidangModel::where('dosen_id', $dosen->dosen_id)->pluck('bidang_id');
            $dosenMatkul = DosenMatkulModel::where('dosen_id', $dosen->dosen_id)->pluck('mk_id');



            // Ambil data role melalui relasi User
            $role = $dosen->user->role;
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $dosen->dosen_id,
                    'nama' => $dosen->user->nama,
                    'username' => $dosen->user->username,
                    'nip' => $dosen->user->nip,
                    'role' => $role->role_nama,  // Menambahkan role
                    'bidang' => $dosenBidang,
                    'jabatan' => $dosen->jabatan_id,
                    'golongan' => $dosen->golongan_id,
                    'pangkat' => $dosen->pangkat_id,
                    'matkul' => $dosenMatkul,
                    'avatar' => $dosen->user->avatar ? asset('avatars/' . $dosen->user->avatar) : asset('avatars/user.jpg')
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
            $dosen = DosenModel::where('user_id', Auth::id())->first();

            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan.'
                ], 404);
            }

            $user = UserModel::where('user_id', Auth::id())->first();

            // Validasi input
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $user->user_id . ',user_id',
                'nama' => 'required|string|max:100',
                'nip' => 'required|string|max:50',
                'bidang' => 'nullable|array',
                'bidang.*' => 'nullable|exists:m_bidang,bidang_id',
                'matakuliah' => 'nullable|array',
                'matakuliah.*' => 'nullable|exists:m_matkul,mk_id',
                'jabatan' => 'nullable|exists:m_jabatan,jabatan_id',
                'golongan' => 'nullable|exists:m_golongan,golongan_id',
                'pangkat' => 'nullable|exists:m_pangkat,pangkat_id',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);



            // Cek perubahan data
            $isChanged = false;

            // Update data user
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->nip = $request->nip;

            // Update data dosen
            // $dosen->bidang_id = $request->bidang_id;
            // $dosen->mk_id = $request->mk_id;
            $dosen->jabatan_id = $request->jabatan;
            $dosen->golongan_id = $request->golongan;
            $dosen->pangkat_id = $request->pangkat;

            DosenBidangModel::where('dosen_id', $dosen->dosen_id)->delete();
            foreach ($request->bidang as $bidang => $value) {
                $newDosenBidang = new DosenBidangModel();
                $newDosenBidang->dosen_id = $dosen->dosen_id;
                $newDosenBidang->bidang_id = $value;
                $newDosenBidang->save();

            }

            DosenMatkulModel::where('dosen_id', $dosen->dosen_id)->delete();
            foreach ($request->matakuliah as $mk => $value) {
                $newDosenMatkul = new DosenMatkulModel();
                $newDosenMatkul->dosen_id = $dosen->dosen_id;
                $newDosenMatkul->mk_id = $value;
                $newDosenMatkul->save();
            }


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

            $dosenBidang = DosenBidangModel::where('dosen_id', $dosen->dosen_id)->get();
            $dosenMatkul = DosenMatkulModel::where('dosen_id', $dosen->dosen_id)->get();

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
            $dosen->save();

            if (
                $user->username != $request->username ||
                $user->nama != $request->nama ||
                $user->nip != $request->nip ||
                $request->hasFile('avatar') ||
                $request->filled('old_password') ||
                $dosen->pangkat_id != $request->pangkat ||
                $dosen->jabatan_id != $request->jabatan ||
                $dosen->golongan_id != $request->golongan
            ) {
                $isChanged = true;
            }

            if (!$isChanged) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan pada profil Anda.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data' => [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'nip' => $user->nip,
                    'jabatan' => $dosen->jabatan_id,
                    'golongan' => $dosen->golongan_id,
                    'pangkat' => $dosen->pangkat_id,
                    'bidang' => $dosenBidang,
                    'matkul' => $dosenMatkul,
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
