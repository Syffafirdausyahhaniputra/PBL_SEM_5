<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Yajra\DataTables\Facades\DataTables;

class RiwayatController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat',
            'subtitle'  => ' '
        ];

        $activeMenu = 'riwayat';

        return view('riwayat.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
