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
        // Ambil dan group data sertifikasi berdasarkan sertif_id
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'sertif_id', 'dosen_id', 'updated_at')
            ->get()
            ->groupBy('sertif_id') // Group data berdasarkan sertif_id
            ->map(function ($groupedItems) {
                $firstItem = $groupedItems->first(); // Ambil item pertama dalam grup

                return [
                    'id' => $firstItem->sertif_id, // Gunakan sertif_id sebagai id
                    'nama' => $firstItem->sertif->nama_sertif,
                    'keterangan' => $firstItem->sertif->keterangan,
                    'status' => $firstItem->sertif->status,
                    'type' => 'sertifikasi', // Tambahkan type sertifikasi
                    'updated_at' => $groupedItems->max('updated_at'), // Ambil updated_at terbaru dalam grup
                ];
            });

        // Ambil dan group data pelatihan berdasarkan pelatihan_id
        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'pelatihan_id', 'dosen_id', 'updated_at')
            ->get()
            ->groupBy('pelatihan_id') // Group data berdasarkan pelatihan_id
            ->map(function ($groupedItems) {
                $firstItem = $groupedItems->first(); // Ambil item pertama dalam grup

                return [
                    'id' => $firstItem->pelatihan_id, // Gunakan pelatihan_id sebagai id
                    'nama' => $firstItem->pelatihan->nama_pelatihan,
                    'keterangan' => $firstItem->pelatihan->keterangan,
                    'status' => $firstItem->pelatihan->status,
                    'type' => 'pelatihan', // Tambahkan type pelatihan
                    'updated_at' => $groupedItems->max('updated_at'), // Ambil updated_at terbaru dalam grup
                ];
            });

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->values()->merge($dataPelatihan->values());

        // Urutkan berdasarkan updated_at
        $sortedData = $data->sortByDesc('updated_at')->values();

        Log::info('Data setelah sorting:', $sortedData->toArray());

        return DataTables::of($sortedData)
            ->addIndexColumn()
            ->make(true);
    }

    public function showSertifikasiAjax($id)
    {
        // Ambil data sertifikasi berdasarkan sertif_id
        $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
            ->where('sertif_id', $id)
            ->get();

        if ($sertifikasi->isEmpty()) {
            abort(404, 'Data sertifikasi tidak ditemukan.');
        }

        // Ambil data pertama untuk informasi umum sertifikasi
        $firstItem = $sertifikasi->first();

        // Ambil daftar dosen yang terkait dengan sertif_id
        $dosenList = $sertifikasi->map(function ($item) {
            return [
                'id' => $item->dosen_id,
                'nama_dosen' => $item->dosen->user->nama ?? 'Tidak Diketahui', // Pastikan ada relasi dosen jika diperlukan
            ];
        })->toArray();

        if (!is_array($dosenList)) {
            Log::error('Dosen list is not an array', ['dosen_list' => $dosenList]);
            $dosenList = []; // Atur default menjadi array kosong
        };

        return view('notifikasi.pimpinan.show_ajax', [
            'nama' => $firstItem->sertif->nama_sertif,
            'bidang' => $firstItem->sertif->bidang->bidang_nama,
            'matkul' => $firstItem->sertif->matkul->mk_nama,
            'vendor' => $firstItem->sertif->vendor->vendor_nama,
            'jenis' => $firstItem->sertif->jenis->jenis_nama,
            'tanggal_acara' => $firstItem->sertif->tanggal,
            'berlaku_hingga' => $firstItem->sertif->masa_berlaku,
            'periode' => $firstItem->sertif->periode,
            'keterangan' => $firstItem->sertif->keterangan,
            'dosen_list' => $dosenList,
        ]);
    }

    public function showPelatihanAjax($id)
    {
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->where('pelatihan_id', $id)
            ->get();

        if ($pelatihan->isEmpty()) {
            abort(404, 'Data pelatihan tidak ditemukan.');
        }

        // Ambil data pertama untuk informasi umum pelatihan
        $firstItem = $pelatihan->first();

        $dosenList = $pelatihan->map(function ($item) {
            return [
                'id' => $item->dosen_id,
                'nama_dosen' => $item->dosen->user->nama ?? 'Tidak Diketahui', // Pastikan ada relasi dosen jika diperlukan
            ];
        })->toArray();

        if (!is_array($dosenList)) {
            Log::error('Dosen list is not an array', ['dosen_list' => $dosenList]);
            $dosenList = []; // Atur default menjadi array kosong
        };


        return view('notifikasi.pimpinan.show_ajax', [
            'nama' => $firstItem->pelatihan->nama_pelatihan,
            'bidang' => $firstItem->pelatihan->bidang->bidang_nama,
            'matkul' => $firstItem->pelatihan->mk_nama,
            'vendor' => $firstItem->pelatihan->vendor->vendor_nama,
            'level' => $firstItem->pelatihan->level->level_nama,
            'tanggal_acara' => $firstItem->pelatihan->tanggal,
            'kuota' => $firstItem->pelatihan->kuota,
            'lokasi' => $firstItem->pelatihan->lokasi,
            'periode' => $firstItem->pelatihan->periode,
            'keterangan' => $firstItem->pelatihan->keterangan,
            'dosen_list' => $dosenList,
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
}
