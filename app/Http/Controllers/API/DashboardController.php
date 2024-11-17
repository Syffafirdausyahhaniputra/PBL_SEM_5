<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BidangModel;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Carbon\Carbon;
use DB;

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

        // Menghitung jumlah sertifikasi dan pelatihan keseluruhan
        $sertifikasiCount = DataSertifikasiModel::count(); // Jumlah sertifikasi
        $pelatihanCount =DataPelatihanModel::count(); // Jumlah pelatihan

        // Menghitung jumlah sertifikasi dan pelatihan keseluruhan
        $jumlahSertifikasiPelatihan = $sertifikasiCount + $pelatihanCount;

        // Mendapatkan data bidang
        $bidang = BidangModel::select('bidang_nama')->get(); // Mendapatkan nama bidang dari tabel BidangModel

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
                'bidang' => $bidang, // Data bidang
            ]
        ], 200);
    }
}
