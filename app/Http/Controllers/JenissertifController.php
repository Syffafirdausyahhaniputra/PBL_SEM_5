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
                /*$btn = '<a href="' . url('/jenis/' . $jenis->jenis_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/jenis/' . $jenis->jenis_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/jenis/' . $jenis->jenis_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                $btn  = '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis/' . $jenis->jenis_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
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
    public function import()
    {
        return view('jenis.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_jenis' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_jenis');  // ambil file dari request 

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
                            'jenis_kode' => $value['A'],
                            'jenis_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    JenisModel::insertOrIgnore($insert);
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
        // ambil data jenis yang akan di export
        $jenis = JenisModel::select('jenis_kode', 'jenis_nama')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Jenis Sertifikasi');
        $sheet->setCellValue('C1', 'Nama Jenis Sertifikasi');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold header
        $no = 1;  // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2

        foreach ($jenis as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->jenis_kode);
            $sheet->setCellValue('C' . $baris, $value->jenis_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data jenis'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data jenis ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $jenis = JenisModel::select('jenis_kode', 'jenis_nama')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('jenis.export_pdf', ['jenis' => $jenis]);

        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data jenis ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
