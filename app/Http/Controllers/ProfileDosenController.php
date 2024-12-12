<?php

namespace App\Http\Controllers;

use App\Models\BidangModel;
use App\Models\UserModel;
use App\Models\DosenModel;
use App\Models\DosenBidangModel;
use App\Models\DosenMatkulModel;
use App\Models\MatkulModel;
use App\Models\JabatanModel;
use App\Models\GolonganModel;
use App\Models\PangkatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        $bidang = BidangModel::all();
        $matkul = MatkulModel::all();

        // Mengambil data tambahan Jabatan, Golongan, dan Pangkat
        $jabatan = JabatanModel::all();
        $golongan = GolonganModel::all();
        $pangkat = PangkatModel::all();

        return view('profile.indexDosen', compact(
            'user', 
            'bidangList', 
            'matkulList', 
            'bidang', 
            'matkul', 
            'jabatan', 
            'golongan', 
            'pangkat'
        ), [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'profileDosen'
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memulai proses pembaruan profil', $request->all());

            $request->validate([
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|string|max:100',
                'nip' => 'required|string|max:50',
                'jabatan_id' => 'required|exists:m_jabatan,jabatan_id',
                'golongan_id' => 'required|exists:m_golongan,golongan_id',
                'pangkat_id' => 'required|exists:m_pangkat,pangkat_id',
                'old_password' => 'nullable|string',
                'password' => 'nullable|min:5',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bidang_id' => 'nullable|array',
                'bidang_id.*' => 'exists:m_bidang,bidang_id',
                'mk_id' => 'nullable|array',
                'mk_id.*' => 'exists:m_matkul,mk_id',
    
            ]);

            $user = UserModel::findOrFail($id);
            $dosen = DosenModel::where('user_id', $user->user_id)->firstOrFail();
            $dosen->update([
                'jabatan_id' => $request->jabatan_id,
                'golongan_id' => $request->golongan_id,
                'pangkat_id' => $request->pangkat_id,
            ]);

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
                if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
                    unlink(public_path('avatars/' . $user->avatar));
                }
                $avatarName = time() . '.' . $request->file('avatar')->extension();
                $request->file('avatar')->move(public_path('avatars'), $avatarName);
                $user->avatar = $avatarName;
            }

            $user->save();

            $this->updateRelatedTable(DosenBidangModel::class, $dosen->dosen_id, 'bidang_id', $request->bidang_id ?? []);
            $this->updateRelatedTable(DosenMatkulModel::class, $dosen->dosen_id, 'mk_id', $request->mk_id ?? []);

            Log::info('Profil berhasil diperbarui', ['user_id' => $user->user_id]);
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui profil: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.', 'errors' => $e->getMessage()], 500);
        }
    }

    private function updateRelatedTable($modelClass, $dosenId, $foreignKey, $newIds = [])
    {
        if (!is_array($newIds)) {
            $newIds = [];
        }

        $existingRecords = $modelClass::where('dosen_id', $dosenId)->get();
        Log::info("Menghapus data lama dan menambahkan data baru untuk {$modelClass}", [
            'dosen_id' => $dosenId,
            'foreignKey' => $foreignKey,
            'newIds' => $newIds,
        ]);

        // Delete records not in the new list
        foreach ($existingRecords as $record) {
            if (!in_array($record->$foreignKey, $newIds)) {
                $record->delete();
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
