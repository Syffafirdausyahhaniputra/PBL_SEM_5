<?php

namespace App\Http\Controllers;

use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ValidasiController extends Controller
{
    // Menampilkan halaman awal validasi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Validasi',
            'subtitle'  => ''
        ];

        $activeMenu = 'validasi'; // set menu yang sedang aktif

        return view('validasi.pimpinan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data validasi dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $statusFilter = $request->input('status');

        $dataSertifikasi = SertifikasiModel::with('data_sertifikasi')
            ->select('sertif_id as id', 'nama_sertif', 'keterangan', 'status')
            ->whereHas('data_sertifikasi', function ($query) use ($statusFilter) {
                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'sertifikasi'
                ];
            })
            ->toArray();

        $dataPelatihan = PelatihanModel::with('data_pelatihan')
            ->select('pelatihan_id as id', 'nama_pelatihan', 'keterangan', 'status')
            ->whereHas('data_pelatihan', function ($query) use ($statusFilter) {
                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'pelatihan'
                ];
            })
            ->toArray();

        $data = array_merge($dataSertifikasi, $dataPelatihan);

        return DataTables::of(collect($data))
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                $btn = '<button onclick="modalAction(\'' . url('/validasi/' . $data['type'] . '/' . $data['id'] . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> validasi</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function show_ajax(string $id)
    {
        // Cari validasi berdasarkan id
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'sertifikasi' // Tambahkan type sertifikasi
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'pelatihan' // Tambahkan type pelatihan
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        // Periksa apakah validasi ditemukan
        if ($data) {
            // Tampilkan halaman show_ajax dengan data validasi
            return view('validasi.show_ajax', ['validasi' => $data]);
        } else {
            // Tampilkan pesan kesalahan jika validasi tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
