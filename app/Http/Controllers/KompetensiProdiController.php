<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use App\Models\BidangModel;
use App\Models\KompetensiProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KompetensiProdiController extends Controller
{
    // Menampilkan halaman awal kompetensi prodi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Kompetensi Prodi',
            'subtitle'  => 'Daftar kompetensi prodi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompetensi_prodi'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi untuk filter prodi
        $bidang = BidangModel::all();

        return view('kompetensi.index', [
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
            ->addColumn('total_bidang', function ($row) {
                // Menampilkan jumlah bidang yang dihitung
                return $row->total_bidang;
            })
            ->addColumn('aksi', function ($kompetensi_prodi) {
                $btn  = '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->prodi->prodi_kode .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensi_prodi/edit_ajax/' . $kompetensi_prodi->prodi_id) . '\')" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->kompetensi_prodi_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Hapus
                </button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        $bidang = BidangModel::select('bidang_id', 'bidang_nama')->get();

        return view('kompetensi.create_ajax', [
            'prodi' => $prodi,
            'bidang' => $bidang,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'prodi_id' => 'required|exists:prodi,prodi_id', // Prodi harus ada di tabel prodi
            'bidang_id' => 'required|array|min:1', // Minimal satu bidang dipilih
            'bidang_id.*' => 'exists:bidang,bidang_id', // Semua bidang harus valid
        ]);

        $prodiId = $request->input('prodi_id');
        $bidangIds = $request->input('bidang_id');

        // Periksa duplikasi di database
        $existing = DB::table('kompetensi_prodi')
            ->where('prodi_id', $prodiId)
            ->whereIn('bidang_id', $bidangIds)
            ->pluck('bidang_id')
            ->toArray();

        // Jika ada duplikasi, kembalikan respons error
        if (!empty($existing)) {
            $existingNames = DB::table('bidang')
                ->whereIn('bidang_id', $existing)
                ->pluck('bidang_nama')
                ->toArray();

            return response()->json([
                'status' => false,
                'message' => 'Bidang berikut sudah terdaftar untuk prodi ini: ' . implode(', ', $existingNames),
            ], 422);
        }

        // Simpan data baru ke database
        foreach ($bidangIds as $bidangId) {
            DB::table('kompetensi_prodi')->insert([
                'prodi_id' => $prodiId,
                'bidang_id' => $bidangId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kembalikan respons sukses
        return response()->json([
            'status' => true,
            'message' => 'Data kompetensi prodi berhasil ditambahkan.',
        ]);
    }

    public function edit_ajax(string $prodi_id)
{
    try {
        $kompetensi_prodi = KompetensiProdiModel::with('bidang')->where('prodi_id', $prodi_id)->first();

        if (!$kompetensi_prodi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // Ambil data Prodi dan Bidang untuk dropdown
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        $bidang = BidangModel::select('bidang_id', 'bidang_nama')->get();

        $bidangList = KompetensiProdiModel::where('prodi_id', $prodi->prodi_id)
                ->with('bidang')
                ->get();

        return view('kompetensi.edit_ajax', [
            'kompetensi_prodi' => $kompetensi_prodi,
            'prodi' => $prodi,
            'bidang' => $bidang,
            'bidangList' => $bidangList,
        ]);
    } catch (\Exception $e) {
        logger()->error("Error di edit_ajax: " . $e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan pada server.'
        ], 500);
    }
}

public function update_ajax(Request $request, $prodi_id)
{
    $kompetensi = KompetensiProdiModel::where('prodi_id', $prodi_id)->first();

    if (!$kompetensi) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'prodi_id' => 'required|exists:prodi,prodi_id',
        'bidang_id' => 'required|array|min:1',
        'bidang_id.*' => 'exists:bidang,bidang_id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'msgField' => $validator->errors()
        ], 422);
    }

    // Update Prodi ID
    $kompetensi->prodi_id = $request->prodi_id;
    $kompetensi->save();

    // Update relasi bidang
    $kompetensi->bidang()->sync($request->bidang_id);

    return response()->json([
        'status' => true,
        'message' => 'Data berhasil diperbarui.'
    ]);
}

    public function confirm_ajax(string $id)
    {
        $kompetensi_prodi = KompetensiProdiModel::find($id);
        return view('kompetensi prodi.confirm_ajax', ['kompetensi prodi' => $kompetensi_prodi]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kompetensi_prodi = KompetensiProdiModel::find($id);
            if ($kompetensi_prodi) {
                $kompetensi_prodi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } else {
            return redirect('/');
        }
    }
    public function show_ajax(string $prodi_kode)
    {
        try {
            // Cari prodi berdasarkan prodi_kode
            $prodi = ProdiModel::where('prodi_kode', $prodi_kode)->first();

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

            return view('kompetensi.show_ajax', [
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
