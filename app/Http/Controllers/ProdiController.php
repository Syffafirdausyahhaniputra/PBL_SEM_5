<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdiController extends Controller
{
    // Menampilkan halaman awal prodi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Program Studi',
            'subtitle'  => 'Daftar program studi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'prodi'; // set menu yang sedang aktif

        return view('prodi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data prodi dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama');

        return DataTables::of($prodi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($prodi) {
                $btn  = '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->prodi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->prodi_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->prodi_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('prodi.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'prodi_kode' => 'required|string|min:2|unique:m_prodi,prodi_kode',
                'prodi_nama' => 'required|string|max:100',
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
            ProdiModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);

        // Jika prodi tidak ditemukan
        if (!$prodi) {
            return response()->json([
                'status' => false,
                'message' => 'Program Studi tidak ditemukan'
            ]);
        }

        return view('prodi.edit_ajax', ['prodi' => $prodi]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_kode' => 'required|string|max:20|unique:m_prodi,prodi_kode,' . $id . ',prodi_id',
                'prodi_nama' => 'required|string|max:100',
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

            $prodi = ProdiModel::find($id);
            if ($prodi) {
                $prodi->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Program Studi berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Program Studi tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.confirm_ajax', ['prodi' => $prodi]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $prodi = ProdiModel::find($id);
            if ($prodi) {
                $prodi->delete();
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
        // Cari prodi berdasarkan id
        $prodi = ProdiModel::find($id);

        // Periksa apakah prodi ditemukan
        if ($prodi) {
            // Tampilkan halaman show_ajax dengan data prodi
            return view('prodi.show_ajax', ['prodi' => $prodi]);
        } else {
            // Tampilkan pesan kesalahan jika prodi tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
