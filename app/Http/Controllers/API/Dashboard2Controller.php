<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\DosenModel;
use App\Models\UserModel;

class Dashboard2Controller extends Controller
{
    public function index(Request $request)
    {
        // Validasi Autentikasi
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Mendapatkan informasi user
        $user = Auth::guard('api')->user();

        // Mendapatkan dosen_id
        $pimpinan = UserModel::where('user_id', $user->user_id)->first();
        if (!$pimpinan) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan untuk user ini.'
            ], 404);
        }
        $userId = $pimpinan->user_id;

        // Mendapatkan Data Sertifikasi dan Pelatihan (Logika WelcomeController)
        $sertifikasiBulan = SertifikasiModel::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan')
            ->distinct()
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        $pelatihanBulan = PelatihanModel::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan')
            ->distinct()
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        $allMonths = collect(array_merge($sertifikasiBulan, $pelatihanBulan))->unique()->sort()->values()->all();

        $sertifikasiPerBulan = DataSertifikasiModel::join('t_sertifikasi', 't_sertifikasi.sertif_id', '=', 't_data_sertifikasi.sertif_id')
            ->selectRaw('DATE_FORMAT(t_sertifikasi.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        $pelatihanPerBulan = DataPelatihanModel::join('t_pelatihan', 't_pelatihan.pelatihan_id', '=', 't_data_pelatihan.pelatihan_id')
            ->selectRaw('DATE_FORMAT(t_pelatihan.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        $sertifikasiData = [];
        $pelatihanData = [];
        foreach ($allMonths as $month) {
            $sertifikasiData[] = $sertifikasiPerBulan[$month] ?? 0;
            $pelatihanData[] = $pelatihanPerBulan[$month] ?? 0;
        }

        $jumlahSertifikasiPelatihan = array_sum($sertifikasiData) + array_sum($pelatihanData);
        $totalPeriode = count($allMonths);
        $rataRataSertifikasiPelatihanPerPeriode = $totalPeriode > 0 ? round($jumlahSertifikasiPelatihan / $totalPeriode, 2) : 0;

        // Format respons API
        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'user' => [
                    'nama' => $user->nama,
                    'role_id' => $user->role_id,
                ],
                'labels' => $allMonths,
                'sertifikasiData' => $sertifikasiData,
                'pelatihanData' => $pelatihanData,
                'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
                'rataRataSertifikasiPelatihanPerPeriode' => $rataRataSertifikasiPelatihanPerPeriode,
            ]
        ], 200);
    }
}
