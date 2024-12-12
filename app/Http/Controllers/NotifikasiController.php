<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'subtitle'  => ' '
        ];

        $activeMenu = 'notifikasi';

        return view('notifikasi.pimpinan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data dalam bentuk json untuk datatables 
    public function list()
    {
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'sertif_id', 'dosen_id', 'updated_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->sertif->keterangan,
                    'status' => $item->sertif->status,
                    'type' => 'sertifikasi', // Tambahkan type sertifikasi
                    'updated_at' => $item->sertif->updated_at
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'pelatihan_id', 'dosen_id', 'updated_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->pelatihan->keterangan,
                    'status' => $item->pelatihan->status,
                    'type' => 'pelatihan', // Tambahkan type pelatihan
                    'updated_at' => $item->pelatihan->updated_at
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        // Urutkan berdasarkan updated_at
        $sortedData = $data->sortByDesc('updated_at')->values();

        Log::info('Data setelah sorting:', $sortedData->toArray());

        return DataTables::of($sortedData)
            ->addIndexColumn()
            ->make(true);
    }

    public function showSertifikasiAjax($id)
    {
        $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
            ->findOrFail($id);

        return view('notifikasi.pimpinan.show_ajax', [
            'nama' => $sertifikasi->sertif->nama_sertif,
            'bidang' => $sertifikasi->sertif->bidang->bidang_nama,
            'matkul' => $sertifikasi->sertif->mk_nama,
            'vendor' => $sertifikasi->sertif->vendor->vendor_nama,
            'jenis' => $sertifikasi->sertif->jenis->jenis_nama,
            'tanggal_acara' => $sertifikasi->sertif->tanggal,
            'berlaku_hingga' => $sertifikasi->sertif->masa_berlaku,
            'periode' => $sertifikasi->sertif->periode
        ]);
    }

    public function showPelatihanAjax($id)
    {
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->findOrFail($id);

        return view('notifikasi.pimpinan.show_ajax', [
            'nama' => $pelatihan->pelatihan->nama_pelatihan,
            'bidang' => $pelatihan->pelatihan->bidang->bidang_nama,
            'matkul' => $pelatihan->pelatihan->mk_nama,
            'vendor' => $pelatihan->pelatihan->vendor->vendor_nama,
            'level' => $pelatihan->pelatihan->level->level_nama,
            'tanggal_acara' => $pelatihan->pelatihan->tanggal,
            'kuota' => $pelatihan->pelatihan->kuota,
            'lokasi' => $pelatihan->pelatihan->lokasi,
            'periode' => $pelatihan->pelatihan->periode
        ]);
    }

    public function getNotifikasiApi()
    {
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'sertifikasi' // Tambahkan type sertifikasi
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'pelatihan' // Tambahkan type pelatihan
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        return response()->json($data, 200);
    }

    public function index2()
    {
        $breadcrumb = (object) [
            'title' => 'Notifikasi Dosen',
            'subtitle'  => ' '
        ];

        $activeMenu = 'notifikasi';

        return view('notifikasi.dosen.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data dalam bentuk json untuk datatables 
    public function list2(Request $request)
    {
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'sertifikasi' // Tambahkan type sertifikasi
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'pelatihan' // Tambahkan type pelatihan
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function showSertifikasiAjax2($id)
    {
        $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
            ->findOrFail($id);

        return view('notifikasi.dosen.show_ajax', [
            'nama' => $sertifikasi->sertif->nama_sertif,
            'bidang' => $sertifikasi->sertif->bidang->bidang_nama,
            'matkul' => $sertifikasi->sertif->mk_nama,
            'vendor' => $sertifikasi->sertif->vendor->vendor_nama,
            'jenis' => $sertifikasi->sertif->jenis->jenis_nama,
            'tanggal_acara' => $sertifikasi->tanggal,
            'berlaku_hingga' => $sertifikasi->masa_berlaku,
            'periode' => $sertifikasi->periode
        ]);
    }

    public function showPelatihanAjax2($id)
    {
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->findOrFail($id);

        return view('notifikasi.show_ajax', [
            'nama' => $pelatihan->pelatihan->nama_pelatihan,
            'bidang' => $pelatihan->pelatihan->bidang->bidang_nama,
            'matkul' => $pelatihan->pelatihan->mk_nama,
            'vendor' => $pelatihan->pelatihan->vendor->vendor_nama,
            'level' => $pelatihan->pelatihan->level->level_nama,
            'tanggal_acara' => $pelatihan->tanggal,
            'kuota' => $pelatihan->kuota,
            'lokasi' => $pelatihan->lokasi,
            'periode' => $pelatihan->periode
        ]);
    }

    public function getNotifikasiApi2()
    {
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'sertifikasi' // Tambahkan type sertifikasi
                ];
            });

        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                    'type' => 'pelatihan' // Tambahkan type pelatihan
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->merge($dataPelatihan);

        return response()->json($data, 200);
    }

    public function export_ajax(Request $request)
    {
        // Retrieve the data you need from the request or session
        $nama = $request->input('nama');
        $bidang = $request->input('bidang');
        $matkul = $request->input('matkul'); // array of mata kuliah objects
        $vendor = $request->input('vendor');
        $jenis = $request->input('jenis');
        $level = $request->input('level');
        $tanggal_acara = $request->input('tanggal_acara');
        $berlaku_hingga = $request->input('berlaku_hingga');
        $periode = $request->input('periode');

        // Create a new PHPWord object
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Add content to the Word document
        $section->addText("Surat Tugas", ['bold' => true, 'size' => 16]);
        $section->addText("Nama: $nama");
        $section->addText("Bidang: $bidang");

        if (!empty($matkul)) {
            $section->addText("Mata Kuliah:");
            foreach ($matkul as $mk) {
                $section->addText("- " . $mk->nama);
            }
        }

        $section->addText("Vendor: $vendor");

        if (isset($jenis)) {
            $section->addText("Jenis: $jenis");
        }

        if (isset($level)) {
            $section->addText("Level: $level");
        }

        $section->addText("Tanggal Acara: $tanggal_acara");

        if (isset($berlaku_hingga)) {
            $section->addText("Berlaku Hingga: $berlaku_hingga");
        }

        $section->addText("Periode: $periode");

        // Save the Word document as a .docx file
        $fileName = 'surat_tugas_' . time() . '.docx';
        $filePath = storage_path("app/public/$fileName");

        $phpWord->save($filePath, 'Word2007');

        // Return the file for download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
