<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdiModel;
use App\Models\BidangModel;
use App\Models\KompetensiProdiModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KompetensiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kompetensi Prodi',
            'subtitle'  => 'Daftar kompetensi prodi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompetensi_prodi'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi untuk filter prodi
        $bidang = BidangModel::all();

        return view('kompetensi.index2', [
            'breadcrumb' => $breadcrumb,
            'prodi' => $prodi,
            'bidang' => $bidang,
            'activeMenu' => $activeMenu
        ]);
    }
    
    // Ambil data kompetensi prodi dalam bentuk json untuk datatables  
    public function list(Request $request)
    {
        $kompetensi_prodis = KompetensiProdiModel::selectRaw('prodi_id, COUNT(bidang_id) as total_bidang')
            ->groupBy('prodi_id')
            ->with('prodi'); // Memuat relasi prodi untuk mendapatkan nama prodi


        return DataTables::of($kompetensi_prodis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('prodi_nama', function ($row) {
                // Mendapatkan nama prodi dari relasi
                return $row->prodi ? $row->prodi->prodi_nama : '-';
            })
            // ->addColumn('bidang', function ($row) {
            //     // Mendapatkan nama prodi dari relasi
            //     // return $row->prodi ? $row->prodi->prodi_nama : '-';
            //     return view('kompetensi.edit_ajax');
            // })
            ->addColumn('bidang', function ($kompetensi_prodi) {
                $btn  = '<button onclick="modalAction(\'' . url('/kompetensi/' . $kompetensi_prodi->prodi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                return $btn;
                // $kompetensi_prodi->prodi->prodi_kode
                // return view('kompetensi.show_ajax');
                // return $kompetensi_prodi->prodi->prodi_kode
            })
            ->rawColumns(['bidang']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show_ajax(string $prodi_id)
    {
        try {
            // Cari prodi berdasarkan prodi_kode
            $prodi = ProdiModel::where('prodi_id', $prodi_id)->first();

            if (!$prodi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Prodi tidak ditemukan'
                ], 404);
            }

            // Debugging: Tampilkan ID prodi
            logger()->info("Prodi ditemukan dengan ID: " . $prodi->prodi_id);

            // Ambil data bidang yang terkait dengan prodi ini
            $bidangList = KompetensiProdiModel::where('prodi_id', $prodi->prodi_id)
                ->with('bidang')
                ->get();

            // Debugging: Tampilkan jumlah bidang yang ditemukan
            logger()->info("Jumlah bidang ditemukan: " . $bidangList->count());

            if ($bidangList->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada bidang terkait untuk prodi ini'
                ], 404);
            }

            return response()->json([
                'prodi' => $prodi,
                'bidangList' => $bidangList,
            ]);
        } catch (\Exception $e) {
            logger()->error("Error di show_ajax: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server. Periksa log untuk detail.'
            ], 500);
        }
    }
}
