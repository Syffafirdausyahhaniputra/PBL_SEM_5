<?php

namespace App\Http\Controllers;

use App\Models\VendorModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class VendorController extends Controller
{
    // Menampilkan halaman awal vendor
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Vendor',
            'subtitle'  => 'Daftar vendor yang terdaftar dalam sistem'
        ];

        $activeMenu = 'vendor'; // set menu yang sedang aktif

        return view('vendor.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data vendor dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $vendors = VendorModel::select('vendor_id', 'vendor_nama', 'vendor_alamat', 'vendor_kota', 'vendor_no_telf', 'vendor_alamat_web');

        return DataTables::of($vendors)
            ->addIndexColumn()
            ->addColumn('aksi', function ($vendor) {
                $btn  = '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('vendor.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'vendor_nama' => 'required|string|min:3|unique:m_vendor,vendor_nama',
                'vendor_alamat' => 'required|string|max:100',
                'vendor_kota' => 'required|string|max:100',
                'vendor_no_telf' => 'required|string|max:100',
                'vendor_alamat_web' => 'required|string|max:200'
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
            VendorModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Vendor berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $vendor = VendorModel::find($id);

        // Jika vendor tidak ditemukan
        if (!$vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Vendor tidak ditemukan'
            ]);
        }

        return view('vendor.edit_ajax', ['vendor' => $vendor]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'vendor_nama' => 'required|string|max:5|unique:m_vendor,vendor_nama,' . $id . ',vendor_id',
                'vendor_alamat' => 'required|string|max:100',
                'vendor_kota' => 'required|string|max:100',
                'vendor_no_telf' => 'required|string|max:100',
                'vendor_alamat_web' => 'required|string|max:200'
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

            $vendor = VendorModel::find($id);
            if ($vendor) {
                $vendor->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Vendor berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Vendor tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $vendor = VendorModel::find($id);
        return view('vendor.confirm_ajax', ['vendor' => $vendor]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $vendor = VendorModel::find($id);
            if ($vendor) {
                $vendor->delete();
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
        // Cari vendor berdasarkan id
        $vendor = VendorModel::find($id);

        // Periksa apakah vendor ditemukan
        if ($vendor) {
            // Tampilkan halaman show_ajax dengan data vendor
            return view('vendor.show_ajax', ['vendor' => $vendor]);
        } else {
            // Tampilkan pesan kesalahan jika vendor tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('vendor.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_vendor' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_vendor');  // ambil file dari request 

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
                            'vendor_nama' => $value['A'],
                            'vendor_alamat' => $value['B'],
                            'vendor_kota' => $value['C'],
                            'vendor_no_telf' => $value['D'],
                            'vendor_alamat_web' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    VendorModel::insertOrIgnore($insert);
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
