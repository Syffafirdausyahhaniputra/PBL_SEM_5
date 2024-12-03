<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
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
            $dosen = DosenModel::with(['user', 'bidang', 'matkul'])
                ->where('user_id', Auth::id())
                ->first();

            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan.'
                ], 404);
            }

            $user = $dosen->user;

            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'nip' => $user->nip,
                    'role' => $user->role->role_nama ?? 'Unknown',
                    'bidang' => $dosen->bidang->bidang_nama ?? null,
                    'matkul' => $dosen->matkul->mk_nama ?? null,
                    'avatar' => $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/user.jpg'),
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

            $user = $dosen->user;

            // Validasi input
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $user->user_id . ',user_id',
                'nama'     => 'required|string|max:100',
                'nip'      => 'required|string|max:50',
                'bidang_id' => 'nullable|exists:m_bidang,bidang_id',
                'mk_id'    => 'nullable|exists:m_matkul,mk_id',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Update data user
            $user->update([
                'username' => $request->username,
                'nama' => $request->nama,
                'nip' => $request->nip,
            ]);

            // Update bidang dan matkul
            $dosen->update([
                'bidang_id' => $request->bidang_id,
                'mk_id' => $request->mk_id,
            ]);

            // Update password jika diperlukan
            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['old_password' => ['Password lama salah']]
                    ]);
                }
                $user->password = Hash::make($request->password);
                $user->save();
            }

            // Update avatar
            if ($request->hasFile('avatar')) {
                // Hapus avatar lama jika ada
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
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data' => [
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'nip' => $user->nip,
                    'bidang' => $dosen->bidang->bidang_nama ?? null,
                    'matkul' => $dosen->matkul->mk_nama ?? null,
                    'avatar' => $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/user.jpg'),
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
