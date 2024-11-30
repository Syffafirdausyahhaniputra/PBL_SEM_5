<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use App\Models\BidangModel;
use App\Models\KompetensiProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                $btn  = '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->prodi->prodi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensi_prodi/edit_ajax/' . $kompetensi_prodi->prodi_id) . '\')" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->prodi_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Hapus
                </button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function index2()
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
    public function list2(Request $request)
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
            ->addColumn('bidang', function ($row) {
                // Mendapatkan nama prodi dari relasi
                // return $row->prodi ? $row->prodi->prodi_nama : '-';
                return view('kompetensi.edit_ajax');
            })
            ->addColumn('aksi', function ($kompetensi_prodi) {
                $btn  = '<button onclick="modalAction(\'' . url('/kompetensi/' . $kompetensi_prodi->prodi->prodi_kode .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                return $btn;
                // $kompetensi_prodi->prodi->prodi_kode
                // return view('kompetensi.show_ajax');
                // return $kompetensi_prodi->prodi->prodi_kode
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

    public function store_ajax(Request $request)
    {
        // Validasi input untuk hanya satu prodi dan minimal satu bidang
        $request->validate([
            'prodi_id' => 'required|exists:m_prodi,prodi_id', // Prodi harus valid dan dipilih
            'bidang_id' => 'required|array|min:1', // Minimal satu bidang dipilih
            'bidang_id.*' => 'exists:m_bidang,bidang_id', // Semua bidang harus valid
        ]);

        $prodiId = $request->input('prodi_id'); // Prodi yang dipilih
        $bidangIds = $request->input('bidang_id'); // Array bidang yang dipilih

        $duplicateEntries = [];
        $newEntries = [];

        // Periksa duplikasi untuk setiap bidang pada prodi yang sama
        foreach ($bidangIds as $bidangId) {
            $existing = DB::table('m_kompetensi_prodi')
                ->where('prodi_id', $prodiId)
                ->where('bidang_id', $bidangId)
                ->exists();

            if ($existing) {
                $duplicateEntries[] = [
                    'prodi_id' => $prodiId,
                    'bidang_id' => $bidangId,
                ];
            } else {
                $newEntries[] = [
                    'prodi_id' => $prodiId,
                    'bidang_id' => $bidangId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Jika ada duplikasi, beri respons error
        if (!empty($duplicateEntries)) {
            $prodiName = DB::table('m_prodi')
                ->where('prodi_id', $prodiId)
                ->pluck('prodi_nama')
                ->first();

            $bidangNames = DB::table('m_bidang')
                ->whereIn('bidang_id', array_column($duplicateEntries, 'bidang_id'))
                ->pluck('bidang_nama', 'bidang_id')
                ->toArray();

            $errorDetails = [];
            foreach ($duplicateEntries as $entry) {
                $errorDetails[] = $prodiName . ' - ' . $bidangNames[$entry['bidang_id']];
            }

            return response()->json([
                'status' => false,
                'message' => 'Kombinasi Prodi dan Bidang berikut sudah terdaftar: ' . implode(', ', $errorDetails),
            ], 422);
        }

        // Simpan data baru ke database
        DB::table('m_kompetensi_prodi')->insert($newEntries);

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

            $bidangList = KompetensiProdiModel::where('prodi_id', $prodi_id)
                ->with('bidang')
                ->get()
                ->pluck('bidang.bidang_id')
                ->toArray();

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
        $request->validate([
            'bidang_id' => 'required|array|min:1',
            'bidang_id.*' => 'distinct|exists:m_bidang,bidang_id',
        ], [
            'bidang_id.min' => 'Setiap prodi harus memiliki minimal satu bidang.',
            'bidang_id.*.distinct' => 'Bidang tidak boleh duplikat.',
            'bidang_id.*.exists' => 'Bidang yang dipilih tidak valid.',
        ]);

        // Ambil data bidang yang sudah ada di database
        $existingBidang = KompetensiProdiModel::where('prodi_id', $prodi_id)
            ->pluck('bidang_id')
            ->toArray();

        $newBidang = $request->bidang_id;

        // Bidang yang akan dihapus (ada di database tetapi tidak ada di input)
        $toBeDeleted = array_diff($existingBidang, $newBidang);

        // Bidang yang akan ditambahkan (ada di input tetapi tidak ada di database)
        $toBeAdded = array_diff($newBidang, $existingBidang);

        try {
            DB::beginTransaction();

            // Hapus bidang yang tidak ada di input
            if (!empty($toBeDeleted)) {
                KompetensiProdiModel::where('prodi_id', $prodi_id)
                    ->whereIn('bidang_id', $toBeDeleted)
                    ->delete();
            }

            // Tambahkan bidang baru
            foreach ($toBeAdded as $bidang_id) {
                KompetensiProdiModel::create([
                    'prodi_id' => $prodi_id,
                    'bidang_id' => $bidang_id,
                ]);
            }

            // Pastikan minimal ada satu bidang tersisa untuk `prodi_id`
            $remainingCount = KompetensiProdiModel::where('prodi_id', $prodi_id)->count();
            if ($remainingCount < 1) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Setiap prodi harus memiliki minimal satu bidang.',
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Error update_ajax: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }

    public function confirm_ajax(string $prodi_id)
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

            return view('kompetensi.confirm_ajax', [
                'prodi' => $prodi,
                'bidangList' => $bidangList,
            ]);
        } catch (\Exception $e) {
            logger()->error("Error di confirm_ajax: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server. Periksa log untuk detail.'
            ], 500);
        }
    }
    public function delete_ajax(Request $request, $prodi_id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Cari semua kompetensi_prodi berdasarkan prodi_id
                $kompetensi_prodi = KompetensiProdiModel::where('prodi_id', $prodi_id)->get();

                // Periksa apakah ada data yang ditemukan
                if ($kompetensi_prodi->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }

                // Hapus seluruh data yang terkait dengan prodi_id
                foreach ($kompetensi_prodi as $data) {
                    $data->delete();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                logger()->error("Error di delete_ajax: " . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan server. Periksa log untuk detail.'
                ], 500);
            }
        } else {
            return redirect('/');
        }
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
