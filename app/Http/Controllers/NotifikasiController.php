<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
            'matkul' => $firstItem->pelatihan->matkul->mk_nama,
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

        $activeMenu = 'notifikasidosen';

        return view('notifikasi.dosen.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data dalam bentuk json untuk datatables 
    public function list2(Request $request)
    {
        // Ambil dosen_id dari user yang sedang login
        $dosenId = Auth::user()->dosen->dosen_id; // Sesuaikan nama field jika berbeda

        // Ambil data sertifikasi berdasarkan dosen_id dan keterangan 'Penunjukan'
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->where('dosen_id', $dosenId)
            ->whereHas('sertif', function ($query) {
                $query->where('keterangan', 'Penunjukan');
            })
            ->select('data_sertif_id as id', 'sertif_id', 'dosen_id', 'updated_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->sertif->nama_sertif,
                    'keterangan' => $item->sertif->keterangan,
                    'status' => $item->sertif->status,
                    'type' => 'sertifikasi',
                    'updated_at' => $item->updated_at
                ];
            });

        // Ambil data pelatihan berdasarkan dosen_id dan keterangan 'Penunjukan'
        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->where('dosen_id', $dosenId)
            ->whereHas('pelatihan', function ($query) {
                $query->where('keterangan', 'Penunjukan');
            })
            ->select('data_pelatihan_id as id', 'pelatihan_id', 'dosen_id', 'updated_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->pelatihan->nama_pelatihan,
                    'keterangan' => $item->pelatihan->keterangan,
                    'status' => $item->pelatihan->status,
                    'type' => 'pelatihan',
                    'updated_at' => $item->updated_at
                ];
            });

        // Konversi menjadi koleksi dasar sebelum digabungkan
        $dataSertifikasiBase = collect($dataSertifikasi)->toBase();
        $dataPelatihanBase = collect($dataPelatihan)->toBase();

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasiBase->merge($dataPelatihanBase);

        // Urutkan berdasarkan updated_at
        $sortedData = $data->sortByDesc('updated_at')->values();

        // Log untuk debugging
        Log::info('Data setelah sorting:', $sortedData->toArray());

        // Return data ke DataTables
        return DataTables::of($sortedData)
            ->addIndexColumn()
            ->make(true);
    }

    public function showSertifikasiAjaxDosen($id)
    {
        // Ambil data sertifikasi berdasarkan sertif_id
        $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
            ->where('data_sertif_id', $id)
            ->first(); // Gunakan first() untuk mendapatkan hanya satu data sertifikasi

        if (!$sertifikasi) {
            abort(404, 'Data sertifikasi tidak ditemukan.');
        }

        // Ambil surat tugas terkait dengan sertifikasi (hanya satu surat tugas)
        $suratTugas = $sertifikasi->suratTugas; // Ambil data surat tugas pertama

        // Persiapkan data surat tugas
        $suratTugasData = [
            'id' => $suratTugas->id ?? null,
            'nama_surat_tugas' => $suratTugas->nama_surat_tugas ?? 'Tidak Diketahui',
            'file_url' => $suratTugas ? asset('dokumen/surat_tugas/' . $suratTugas->id . '.pdf') : null, // URL file surat tugas
        ];

        return view('notifikasi.dosen.show_ajax', [
            'nama' => $sertifikasi->sertif->nama_sertif,
            'bidang' => $sertifikasi->sertif->bidang->bidang_nama,
            'matkul' => $sertifikasi->sertif->matkul->mk_nama,
            'vendor' => $sertifikasi->sertif->vendor->vendor_nama,
            'jenis' => $sertifikasi->sertif->jenis->jenis_nama,
            'tanggal_acara' => $sertifikasi->sertif->tanggal,
            'berlaku_hingga' => $sertifikasi->sertif->masa_berlaku,
            'periode' => $sertifikasi->sertif->periode,
            'keterangan' => $sertifikasi->sertif->keterangan,
            'surat_tugas' => $suratTugasData, // Hanya satu surat tugas
        ]);
    }

    public function showPelatihanAjaxDosen($id)
    {
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->where('data_pelatihan_id', $id)
            ->first(); // Gunakan first() untuk mendapatkan hanya satu data pelatihan

        if (!$pelatihan) {
            abort(404, 'Data pelatihan tidak ditemukan.');
        }

        // Ambil surat tugas terkait pelatihan (hanya satu surat tugas)
        $suratTugas = $pelatihan->surat_tugas; // Ambil data surat tugas pertama

        // Persiapkan data surat tugas
        $suratTugasData = [
            'id' => $suratTugas->surat_tugas_id ?? null,
            'nama_surat_tugas' => $suratTugas->nama_surat ?? 'Tidak Diketahui',
            'file_url' => $suratTugas ? asset('dokumen/surat_tugas/' . $suratTugas->nama_surat ) : null, // URL file surat tugas
        ];

        return view('notifikasi.dosen.show_ajax', [
            'nama' => $pelatihan->pelatihan->nama_pelatihan,
            'bidang' => $pelatihan->pelatihan->bidang->bidang_nama,
            'matkul' => $pelatihan->pelatihan->matkul->mk_nama,
            'vendor' => $pelatihan->pelatihan->vendor->vendor_nama,
            'level' => $pelatihan->pelatihan->level->level_nama,
            'tanggal_acara' => $pelatihan->pelatihan->tanggal,
            'berlaku_hingga' => $pelatihan->pelatihan->masa_berlaku,
            'periode' => $pelatihan->pelatihan->periode,
            'keterangan' => $pelatihan->pelatihan->keterangan,
            'surat_tugas' => $suratTugasData, // Hanya satu surat tugas
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
