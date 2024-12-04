<?php

namespace App\Http\Controllers;

use App\Models\BidangModel;
use App\Models\UserModel;
use App\Models\DosenModel;
use App\Models\DosenBidangModel;
use App\Models\DosenMatkulModel;
use App\Models\MatkulModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileDosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bidangList = DosenBidangModel::where('dosen_id', $user->dosen->dosen_id)
            ->with('bidang')
            ->get();
        $matkulList = DosenMatkulModel::where('dosen_id', $user->dosen->dosen_id)
            ->with('matkul')
            ->get();

        $breadcrumb = (object) [
            'title' => 'Profile',
            'subtitle' => 'Bio data diri pengguna'
        ];

        return view('profile.indexDosen', compact('user', 'bidangList', 'matkulList'), [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'profileDosen'
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|string|max:100',
                'nip' => 'required|string|max:50',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bidang_ids' => 'nullable|array',
                'matkul_ids' => 'nullable|array',
            ]);

            $user = UserModel::findOrFail($id);
            $dosen = DosenModel::where('user_id', $user->user_id)->firstOrFail();

            // Update m_user table
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->nip = $request->nip;

            if ($request->filled('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json(['success' => false, 'errors' => ['old_password' => ['Password lama salah']]], 422);
                }
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                $avatarName = time() . '.' . $request->file('avatar')->extension();
                $request->file('avatar')->move(public_path('avatars'), $avatarName);
                $user->avatar = $avatarName;
            }

            $user->save();

            // Update m_dosen_bidang
            $this->updateRelatedTable(DosenBidangModel::class, $dosen->dosen_id, 'bidang_id', $request->bidang_ids);

            // Update m_dosen_matkul
            $this->updateRelatedTable(DosenMatkulModel::class, $dosen->dosen_id, 'mk_id', $request->matkul_ids);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    private function updateRelatedTable($modelClass, $dosenId, $foreignKey, $newIds = [])
    {
        $existingRecords = $modelClass::where('dosen_id', $dosenId)->get();

        // Delete records not in the new list
        foreach ($existingRecords as $record) {
            if (!in_array($record->$foreignKey, $newIds)) {
                $existingRecords->count() > 1 && $record->delete();
            }
        }

        // Add new records
        foreach ($newIds as $id) {
            if (!$existingRecords->contains($foreignKey, $id)) {
                $modelClass::create([
                    'dosen_id' => $dosenId,
                    $foreignKey => $id
                ]);
            }
        }
    }
}
