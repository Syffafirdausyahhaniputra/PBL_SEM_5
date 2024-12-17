<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiModel;
use App\Models\SertifikasiModel;
use App\Models\DataPelatihanModel;
use App\Models\PelatihanModel;

class Welcome2Controller extends Controller
{
    public function index2()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'subtitle' => 'Halo, ' . Auth::user()->nama . '!'
        ];

        $activeMenu = 'dashboard';

        // Ambil dosen_id dari user yang sedang login
        $dosenId = Auth::user()->dosen->dosen_id; // Sesuaikan nama field jika berbeda

        // Ambil data sertifikasi berdasarkan dosen_id dan keterangan 'Penunjukan'
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->where('dosen_id', $dosenId)
            ->select('data_sertif_id as id', 'sertif_id', 'dosen_id', 'updated_at')
            ->get();

        // Ambil data pelatihan berdasarkan dosen_id dan keterangan 'Penunjukan'
        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->where('dosen_id', $dosenId)
            ->select('data_pelatihan_id as id', 'pelatihan_id', 'dosen_id', 'updated_at')
            ->get();


        // Menghitung jumlah sertifikasi dan pelatihan
        $jumlahSertifikasiPelatihan = $dataSertifikasi->count() + $dataPelatihan->count();

        return view('welcome2', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
            'sertifikasi' => $dataSertifikasi,
            'pelatihan' => $dataPelatihan
        ]);
    }

    // Menambahkan fungsi untuk mendapatkan data bulan, sertifikasi, dan pelatihan per bulan
    private function getAllMonths()
    {
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

        return collect(array_merge($sertifikasiBulan, $pelatihanBulan))->unique()->sort()->values()->all();
    }

    private function getSertifikasiPerBulan()
    {
        return $sertifikasiPerBulan = DataSertifikasiModel::join('t_sertifikasi', 't_sertifikasi.sertif_id', '=', 't_data_sertifikasi.sertif_id')
            ->selectRaw('DATE_FORMAT(t_sertifikasi.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();
    }

    private function getPelatihanPerBulan()
    {
        return $pelatihanPerBulan = DataPelatihanModel::join('t_pelatihan as pelatihan', 'pelatihan.pelatihan_id', '=', 't_data_pelatihan.pelatihan_id')
            ->selectRaw('DATE_FORMAT(pelatihan.tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();
    }
}
