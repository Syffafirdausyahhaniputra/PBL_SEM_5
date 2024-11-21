<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataPelatihanModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PelatihanController extends Controller
{
    // Method untuk menampilkan daftar pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pelatihan',
            'subtitle' => 'Daftar Pelatihan' // Tambahkan jika subtitle diperlukan
        ];

        $pelatihan = PelatihanModel::all(); // Mengambil data pelatihan dari database

        return view('pelatihan.index', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
            'pelatihan' => $pelatihan,  // Mengirim data pelatihan ke view
            'breadcrumb' => $breadcrumb // Menyertakan breadcrumb ke view
        ]);
    }


    // Method untuk menampilkan form tambah pelatihan
    public function create()
    {
        return view('pelatihan.create', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
        ]);
    }
    public function show_ajax($id)
    {
        try {
            // Ambil data pelatihan beserta relasi terkait
            $pelatihan = PelatihanModel::with([
                'level',      // Relasi ke LevelPelatihanModel
                'bidang',     // Relasi ke BidangModel
                'matkul',     // Relasi ke MatkulModel
                'vendor',     // Relasi ke VendorModel
                'dataPelatihan.dosen' // Relasi ke DataPelatihanModel dan DosenModel
            ])->findOrFail($id);

            // Format data untuk dikirimkan
            return response()->json([
                'success' => true,
                'data' => $pelatihan,
            ]);
        } catch (\Exception $e) {
            // Handle error jika pelatihan tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }


    // Method untuk menyimpan data pelatihan baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        PelatihanModel::create($validatedData); // Menyimpan data ke database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan!');
    }

    // Method untuk menampilkan form edit pelatihan
    public function edit($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID

        return view('pelatihan.edit', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
            'pelatihan' => $pelatihan, // Mengirim data pelatihan ke view
        ]);
    }

    // Method untuk memperbarui data pelatihan
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID
        $pelatihan->update($validatedData); // Memperbarui data pelatihan di database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui!');
    }

    // Method untuk menghapus data pelatihan
    public function destroy($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID
        $pelatihan->delete(); // Menghapus data pelatihan dari database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil dihapus!');
    }

    public function list(Request $request)
    {
        $dataPelatihan = DataPelatihanModel::with(['pelatihan', 'dosen'])->get();

        $response = [];
        foreach ($dataPelatihan as $item) {
            $response[] = [
                'data_pelatihan_id' => $item->data_pelatihan_id,
                'nama_pelatihan' => $item->pelatihan->nama_pelatihan ?? '-', // Mengambil nama pelatihan
                'nama_dosen' => $item->dosen->nama_dosen ?? '-', // Mengambil nama dosen
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'), // Format tanggal
            ];
        }

        return response()->json([
            'data' => $response // Respons JSON untuk DataTables
        ]);
    }

    public function create_ajax()
    {
        // Ambil data tambahan jika diperlukan
        return view('pelatihan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|integer',
                'lokasi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Pelatihan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax($id)
    {
        $pelatihan = PelatihanModel::find($id);

        return view('pelatihan.edit_ajax', ['pelatihan' => $pelatihan]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|integer',
                'lokasi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Pelatihan berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_pelatihan' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_pelatihan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $index => $row) {
                    if ($index > 1) {
                        $insert[] = [
                            'nama_pelatihan' => $row['A'],
                            'tanggal' => $row['B'],
                            'kuota' => $row['C'],
                            'lokasi' => $row['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    PelatihanModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $pelatihan = PelatihanModel::all();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pelatihan');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Kuota');
        $sheet->setCellValue('E1', 'Lokasi');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($pelatihan as $index => $item) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $item->nama_pelatihan);
            $sheet->setCellValue("C{$row}", $item->tanggal);
            $sheet->setCellValue("D{$row}", $item->kuota);
            $sheet->setCellValue("E{$row}", $item->lokasi);
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Pelatihan ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $pelatihan = PelatihanModel::all();
        $pdf = Pdf::loadView('pelatihan.export_pdf', ['pelatihan' => $pelatihan]);
        return $pdf->stream('Data Pelatihan ' . date('Y-m-d H:i:s') . '.pdf');
    }

}
