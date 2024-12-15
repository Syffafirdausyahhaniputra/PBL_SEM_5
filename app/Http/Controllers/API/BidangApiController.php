<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use App\Models\DosenBidangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BidangApiController extends Controller
{
    public function index()
    {
        $bidang = BidangModel::all();

        // Jika data bidang kosong
        if ($bidang->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data bidang tidak ditemukan'
            ], 404);
        }

        // Jika data bidang tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $bidang
        ]);
    }

    public function show($id) //show bidang by dosen
    {
        // Ambil data bidang berdasarkan ID
        $bidang = BidangModel::findOrFail($id);

        // Ambil daftar dosen berdasarkan bidang dengan relasi
        $dosen = DosenBidangModel::where('bidang_id', $id)
            ->with('dosen2.user')
            ->get();

        $breadcrumb = (object) [
            'title' => $bidang->bidang_nama,
            'subtitle' => 'List dosen bidang ' . $bidang->bidang_nama
        ];

        // Tampilkan data dosen
        return response()->json([
            'status' => 'success',
            'bidang' => $bidang,
            'dosen' => $dosen,
        ]);
    }

    public function showDosen($id, $id_dosen)
    {
        $bidang = BidangModel::findOrFail($id);

        // Ambil data dosen spesifik berdasarkan bidang dan id_dosen
        $dosen = DosenBidangModel::where('bidang_id', $id)
            ->whereHas('dosen2', function ($query) use ($id_dosen) {
                $query->where('dosen_id', $id_dosen);
            })
            ->with('dosen2.user')
            ->firstOrFail();

        // Ambil data sertifikasi yang terkait dengan dosen dan bidang melalui relasi dosenBidang
        $sertifikasi = DataSertifikasiModel::with('sertif', 'sertif.bidang.dosenBidang')
            ->where('dosen_id', $id_dosen)
            ->whereHas('sertif.bidang.dosenBidang', function ($query) use ($id, $id_dosen) {
                $query->where('bidang_id', $id)
                    ->where('dosen_id', $id_dosen);
            })
            ->get();
        Log::info($sertifikasi);

        // Ambil data pelatihan yang terkait dengan dosen dan bidang melalui relasi dosenBidang
        $pelatihan = DataPelatihanModel::with('pelatihan', 'pelatihan.bidang.dosenBidang')
            ->where('dosen_id', $id_dosen)
            ->whereHas('pelatihan.bidang.dosenBidang', function ($query) use ($id, $id_dosen) {
                $query->where('bidang_id', $id)
                    ->where('dosen_id', $id_dosen);
            })
            ->get();

        // Hitung jumlah sertifikasi dan pelatihan
        $jumlahSertifikasiPelatihan = $sertifikasi->count() + $pelatihan->count();

        // Tampilkan view dengan data dosen
        return response()->json([
            'bidang' => $bidang,
            'dosen' => $dosen,
            'sertifikasi' => $sertifikasi,
            'pelatihan' => $pelatihan,
            'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
        ]);
    }
}
