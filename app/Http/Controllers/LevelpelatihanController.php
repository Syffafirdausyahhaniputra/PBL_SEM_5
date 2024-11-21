<?php

namespace App\Http\Controllers;

use App\Models\LevelPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelpelatihanController extends Controller
{
    // Menampilkan halaman awal level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Level Pelatihan',
            'subtitle'  => 'Daftar level pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data level dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $levels = LevelPelatihanModel::select('level_id', 'level_nama');

        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn  = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'level_kode' => 'required|string|min:2|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];

            // Validasi data inputan
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Simpan data ke database
            LevelPelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Level Pelatihan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $level = LevelPelatihanModel::find($id);

        // Jika level tidak ditemukan
        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'Level Pelatihan tidak ditemukan'
            ]);
        }

        return view('level.edit_ajax', ['level' => $level]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:20|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100',
            ];

            // Validasi data input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelPelatihanModel::find($id);
            if ($level) {
                $level->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Level Pelatihan berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Level Pelatihan tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $level = LevelPelatihanModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelPelatihanModel::find($id);
            if ($level) {
                $level->delete();
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
    public function show_ajax(string $id)
    {
        // Cari level berdasarkan id
        $level = LevelPelatihanModel::find($id);

        // Periksa apakah level ditemukan
        if ($level) {
            // Tampilkan halaman show_ajax dengan data level
            return view('level.show_ajax', ['level' => $level]);
        } else {
            // Tampilkan pesan kesalahan jika level tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
