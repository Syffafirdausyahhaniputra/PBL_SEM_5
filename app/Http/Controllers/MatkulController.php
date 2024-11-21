<?php

namespace App\Http\Controllers;

use App\Models\MatkulModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class MatkulController extends Controller
{
    // Menampilkan halaman awal matkul
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Mata Kuliah',
            'subtitle'  => 'Daftar matak kuliah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'matkul'; // set menu yang sedang aktif

        return view('matkul.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data matkul dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $matkuls = MatkulModel::select('mk_id', 'mk_nama');

        return DataTables::of($matkuls)
            ->addIndexColumn()
            ->addColumn('aksi', function ($matkul) {
                $btn  = '<button onclick="modalAction(\'' . url('/matkul/' . $matkul->mk_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/matkul/' . $matkul->mk_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/matkul/' . $matkul->mk_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('matkul.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'mk_kode' => 'required|string|min:2|unique:m_matkul,mk_kode',
                'mk_nama' => 'required|string|max:100',
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
            MatkulModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Mata kuliah berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $matkul = MatkulModel::find($id);

        // Jika matkul tidak ditemukan
        if (!$matkul) {
            return response()->json([
                'status' => false,
                'message' => 'Mata kuliah tidak ditemukan'
            ]);
        }

        return view('matkul.edit_ajax', ['matkul' => $matkul]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mk_kode' => 'required|string|max:20|unique:m_matkul,mk_kode,' . $id . ',mk_id',
                'mk_nama' => 'required|string|max:100',
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

            $matkul = MatkulModel::find($id);
            if ($matkul) {
                $matkul->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Mata kuliah berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Mata kuliah tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $matkul = MatkulModel::find($id);
        return view('matkul.confirm_ajax', ['matkul' => $matkul]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $matkul = MatkulModel::find($id);
            if ($matkul) {
                $matkul->delete();
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
        // Cari matkul berdasarkan id
        $matkul = MatkulModel::find($id);

        // Periksa apakah matkul ditemukan
        if ($matkul) {
            // Tampilkan halaman show_ajax dengan data matkul
            return view('matkul.show_ajax', ['matkul' => $matkul]);
        } else {
            // Tampilkan pesan kesalahan jika matkul tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('matkul.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_matkul' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_matkul');  // ambil file dari request 

            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 

            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 

            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati 
                        $insert[] = [
                            'mk_kode' => $value['A'],
                            'mk_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    MatkulModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
}
