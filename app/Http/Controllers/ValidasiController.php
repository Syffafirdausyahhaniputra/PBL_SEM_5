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
                $btn = '<button onclick="modalAction(\'' . url('/validasi/' . $data['type'] . '/' . $data['id'] . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Validasi</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

//syffa
    // public function show_ajax(string $type, string $id)
    // {
    //     if ($type === 'sertifikasi') {
    //         $data = DataSertifikasiModel::with('sertif', 'dosen')
    //             ->where('data_sertif_id', $id)
    //             ->first();
    //         if ($data) {
    //             return response()->json([
    //                 'status' => true,
    //                 'validasi' => [
    //                     'type' => 'sertifikasi',
    //                     'nama' => $data->sertif->nama_sertif,
    //                     'keterangan' => $data->keterangan,
    //                     'status' => $data->status,
    //                     'peserta' => $data->dosen->map(function ($d) {
    //                         return ['nama' => $d->nama];
    //                     })
    //                 ]
    //             ]);
    //         }
    //     } elseif ($type === 'pelatihan') {
    //         $data = DataPelatihanModel::with('pelatihan', 'dosen')
    //             ->where('data_pelatihan_id', $id)
    //             ->first();
    //         if ($data) {
    //             return response()->json([
    //                 'status' => true,
    //                 'validasi' => [
    //                     'type' => 'pelatihan',
    //                     'nama' => $data->pelatihan->nama_pelatihan,
    //                     'keterangan' => $data->keterangan,
    //                     'status' => $data->status,
    //                     'peserta' => $data->dosen->map(function ($d) {
    //                         return ['nama' => $d->nama];
    //                     })
    //                 ]
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Data tidak ditemukan'
    //     ]);
    // }
    public function show_ajax(string $type, string $id)
    {
        if ($type === 'sertifikasi') {
            $data = SertifikasiModel::with('data_sertifikasi', 'dosen')
                ->where('sertif_id', $id)
                ->first();
    
            if ($data) {
                return response()->json([
                    'status' => true,
                    'validasi' => [
                        'type' => 'sertifikasi',
                        'nama' => $data->data_sertifikasi->nama_sertif,
                        'keterangan' => $data->keterangan,
                        'status' => $data->status,
                        'peserta' => $data->dosen->map(function ($dosen) {
                            return ['nama' => $dosen->nama];
                        })
                    ]
                ]);
            }
        } elseif ($type === 'pelatihan') {
            $data = PelatihanModel::with('data_pelatihan', 'dosen')
                ->where('pelatihan_id', $id)
                ->first();
    
            if ($data) {
                return response()->json([
                    'status' => true,
                    'validasi' => [
                        'type' => 'pelatihan',
                        'nama' => $data->data_pelatihan->nama_pelatihan,
                        'keterangan' => $data->keterangan,
                        'status' => $data->status,
                        'peserta' => $data->dosen->map(function ($dosen) {
                            return ['nama' => $dosen->nama];
                        })
                    ]
                ]);
            }
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
    }
    
}
