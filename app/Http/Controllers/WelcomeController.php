<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\BidangModel;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Carbon\Carbon;
use DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'subtitle' => 'Halo, ' . Auth::user()->nama . '!'
        ];

        $activeMenu = 'dashboard';

        // Mendapatkan bulan yang ada di SertifikasiModel dan PelatihanModel
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

        // Menggabungkan kedua array bulan dan menghapus duplikatnya
        $allMonths = collect(array_merge($sertifikasiBulan, $pelatihanBulan))->unique()->sort()->values()->all();

        // Mendapatkan jumlah sertifikasi per bulan
        $sertifikasiPerBulan = DataSertifikasiModel::join('t_sertifikasi', 't_sertifikasi.sertif_id', '=', 't_data_sertifikasi.sertif_id')
            ->selectRaw('DATE_FORMAT(t_sertifikasi.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        // Mendapatkan jumlah pelatihan per bulan
        $pelatihanPerBulan = DataPelatihanModel::join('t_pelatihan', 't_pelatihan.pelatihan_id', '=', 't_data_pelatihan.pelatihan_id')
            ->selectRaw('DATE_FORMAT(t_pelatihan.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        // Menyiapkan data sertifikasi dan pelatihan per bulan berdasarkan allMonths
        $sertifikasiData = [];
        $pelatihanData = [];
        foreach ($allMonths as $month) {
            $sertifikasiData[] = $sertifikasiPerBulan[$month] ?? 0;
            $pelatihanData[] = $pelatihanPerBulan[$month] ?? 0;
        }

        // Menghitung jumlah sertifikasi dan pelatihan keseluruhan
        $jumlahSertifikasiPelatihan = array_sum($sertifikasiData) + array_sum($pelatihanData);

        // Menghitung rata-rata sertifikasi dan pelatihan per periode
        $totalPeriode = count($allMonths);
        $rataRataSertifikasiPelatihanPerPeriode = $totalPeriode > 0 ? round($jumlahSertifikasiPelatihan / $totalPeriode, 2) : 0;

        // Mendapatkan data bidang
        $bidang = BidangModel::all();

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'labels' => $allMonths,
            'sertifikasiData' => $sertifikasiData,
            'pelatihanData' => $pelatihanData,
            'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
            'rataRataSertifikasiPelatihanPerPeriode' => $rataRataSertifikasiPelatihanPerPeriode,
            'bidang' => $bidang
        ]);
    }
}
