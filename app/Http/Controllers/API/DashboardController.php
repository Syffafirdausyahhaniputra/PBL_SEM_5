<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use App\Models\DosenModel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah user sudah terautentikasi
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Mendapatkan informasi user
        $user = Auth::guard('api')->user();

        // Mendapatkan dosen_id berdasarkan user_id
        $dosen = DosenModel::where('user_id', $user->user_id)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan untuk user ini.'
            ], 404);
        }

        $dosenId = $dosen->dosen_id;

        // Menghitung jumlah sertifikasi dan pelatihan berdasarkan dosen_id
        $sertifikasiCount = DataSertifikasiModel::where('dosen_id', $dosenId)->count();
        $pelatihanCount = DataPelatihanModel::where('dosen_id', $dosenId)->count();
        $jumlahSertifikasiPelatihan = $sertifikasiCount + $pelatihanCount;

        // Mendapatkan detail sertifikasi
        $sertifikasi = DataSertifikasiModel::where('dosen_id', $dosenId)
            ->with(['sertif.jenis', 'sertif.bidang'])
            ->get()
            ->map(function ($data) {
                return [
                    'nama_sertifikasi' => $data->sertif->nama_sertif ?? '-',
                    'bidang_sertifikasi' => $data->sertif->bidang->bidang_nama ?? '-',
                    'masa_berlaku' => $data->sertif->masa_berlaku ?? '-',
                ];
            });

        // Mendapatkan detail pelatihan
        $pelatihan = DataPelatihanModel::where('dosen_id', $dosenId)
            ->with(['pelatihan.bidang'])
            ->get()
            ->map(function ($data) {
                return [
                    'nama_pelatihan' => $data->pelatihan->nama_pelatihan ?? '-',
                    'bidang_pelatihan' => $data->pelatihan->bidang->bidang_nama ?? '-',
                    'masa_berlaku' => $data->pelatihan->tanggal ?? '-', // Asumsi masa berlaku menggunakan 'periode'
                ];
            });

        // Menyiapkan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'user' => [
                    'nama' => $user->nama,
                    'role_id' => $user->role_id,
                ],
                'jumlahSertifikasi' => $sertifikasiCount,
                'jumlahPelatihan' => $pelatihanCount,
                'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
                'sertifikasi' => $sertifikasi,
                'pelatihan' => $pelatihan,
            ]
        ], 200);
    }
}