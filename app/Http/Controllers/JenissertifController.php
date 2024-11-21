<?php

namespace App\Http\Controllers;

use App\Models\JenisModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class JenissertifController extends Controller
{
    // Menampilkan halaman awal jenis
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Jenis Sertifikasi',
            'subtitle'  => 'Daftar jenis sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jenis'; // set menu yang sedang aktif

        return view('jenis.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data jenis dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $jeniss = JenisModel::select('jenis_id', 'jenis_nama');

        return DataTables::of($jeniss)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis) {
                $btn  = '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('jenis.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'jenis_kode' => 'required|string|min:3|unique:m_jenis,jenis_kode',
                'jenis_nama' => 'required|string|max:100',
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
            JenisModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Jenis Sertifikasi berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $jenis = JenisModel::find($id);

        // Jika jenis tidak ditemukan
        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis Sertifikasi tidak ditemukan'
            ]);
        }

        return view('jenis.edit_ajax', ['jenis' => $jenis]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_kode' => 'required|string|max:20|unique:m_jenis,jenis_kode,' . $id . ',jenis_id',
                'jenis_nama' => 'required|string|max:100',
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

            $jenis = JenisModel::find($id);
            if ($jenis) {
                $jenis->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Jenis Sertifikasi berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Jenis Sertifikasi tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $jenis = JenisModel::find($id);
        return view('jenis.confirm_ajax', ['jenis' => $jenis]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $jenis = JenisModel::find($id);
            if ($jenis) {
                $jenis->delete();
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
        // Cari jenis berdasarkan id
        $jenis = JenisModel::find($id);

        // Periksa apakah jenis ditemukan
        if ($jenis) {
            // Tampilkan halaman show_ajax dengan data jenis
            return view('jenis.show_ajax', ['jenis' => $jenis]);
        } else {
            // Tampilkan pesan kesalahan jika jenis tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
