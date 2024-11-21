<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use App\Models\BidangModel;
use App\Models\KompetensiProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
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
                $btn  = '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->kompetensi_prodi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensi_prodi/' . $kompetensi_prodi->kompetensi_prodi_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">
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


    public function edit_ajax(string $id)
    {
        $kompetensi_prodi = KompetensiProdiModel::find($id);
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        $bidang = BidangModel::select('prodi_id', 'bidang_nama')->get();

        return view('kompetensi prodi.edit_ajax', ['kompetensi prodi' => $kompetensi_prodi, 'prodi' => $prodi, 'bidang' => $bidang]);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_id' => 'required|integer',
                'bidang_id' => 'required|integer',
            ];
            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = BidangModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
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
    public function show_ajax(string $id)
    {
        // Cari kompetensi prodi berdasarkan id
        $kompetensi_prodi = KompetensiProdiModel::find($id);

        // Periksa apakah kompetensi prodi ditemukan
        if ($kompetensi_prodi) {
            // Tampilkan halaman show_ajax dengan data kompetensi prodi
            return view('kompetensi prodi.show_ajax', ['kompetensi prodi' => $kompetensi_prodi]);
        } else {
            // Tampilkan pesan kesalahan jika kompetensi prodi tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('kompetensi prodi.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_kompetensi_prodi' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kompetensi_prodi');  // ambil file dari request 

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
                            'prodi_id' => $value['A'],
                            'kompetensi prodiname' => $value['B'],
                            'nama' => $value['C'],
                            'nip' => $value['D'],
                            'password' => Hash::make($value['E']),
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    BidangModel::insertOrIgnore($insert);
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
    public function export_excel()
    {
        // ambil data kompetensi prodi yang akan di export
        $kompetensi_prodi = BidangModel::select('prodi_id', 'kompetensi prodiname', 'nama', 'nip', 'password')
            ->orderBy('prodi_id')
            ->with('prodi')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'kompetensi prodiname');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'NIP');
        $sheet->setCellValue('E1', 'Password');
        $sheet->setCellValue('F1', 'Jabatan');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header
        $no = 1;  // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2

        foreach ($kompetensi_prodi as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kompetensi_prodiname);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->nip);
            $sheet->setCellValue('E' . $baris, $value->password);
            $sheet->setCellValue('F' . $baris, $value->prodi->prodi_nama); // ambil nama kategori
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data kompetensi prodi'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data kompetensi prodi ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    } // end function export excel
    public function export_pdf()
    {
        $kompetensi_prodi = BidangModel::select('prodi_id', 'kompetensi prodiname', 'nama', 'nip')
            ->orderBy('prodi_id')
            ->orderBy('kompetensi prodiname')
            ->with('prodi')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kompetensi prodi.export_pdf', ['kompetensi prodi' => $kompetensi_prodi]);

        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data kompetensi prodi ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
