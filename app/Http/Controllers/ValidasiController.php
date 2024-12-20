<?php

namespace App\Http\Controllers;

use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ValidasiController extends Controller
{
    // Menampilkan halaman awal validasi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Validasi',
            'subtitle'  => ''
        ];

        $activeMenu = 'validasi'; // set menu yang sedang aktif

        return view('validasi.pimpinan.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data validasi dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $keteranganFilter = $request->input('keterangan');

        // Ambil data sertifikasi
        $dataSertifikasi = DataSertifikasiModel::with('sertif')
            ->select('data_sertif_id as id', 'sertif_id', 'dosen_id', 'updated_at')
            ->whereHas('sertif', function ($query) use ($keteranganFilter) {
                if ($keteranganFilter) {
                    $query->where('keterangan', $keteranganFilter);
                }
            })
            ->get()
            ->groupBy('sertif_id')
            ->map(function ($groupedItems) {
                $firstItem = $groupedItems->first(); // Ambil item pertama
                if (is_object($firstItem) && $firstItem->sertif) {
                    return [
                        'id' => $firstItem->sertif_id,
                        'nama' => $firstItem->sertif->nama_sertif,
                        'keterangan' => $firstItem->sertif->keterangan,
                        'status' => $firstItem->sertif->status,
                        'type' => 'sertifikasi',
                        'updated_at' => $groupedItems->max('updated_at'),
                    ];
                }
                return null; // Return null jika datanya tidak valid
            })->filter(); // Hapus item null dari koleksi

        // Ambil data pelatihan
        $dataPelatihan = DataPelatihanModel::with('pelatihan')
            ->select('data_pelatihan_id as id', 'pelatihan_id', 'dosen_id', 'updated_at')
            ->whereHas('pelatihan', function ($query) use ($keteranganFilter) {
                if ($keteranganFilter) {
                    $query->where('keterangan', $keteranganFilter);
                }
            })
            ->get()
            ->groupBy('pelatihan_id')
            ->map(function ($groupedItems) {
                $firstItem = $groupedItems->first(); // Ambil item pertama
                if (is_object($firstItem) && $firstItem->pelatihan) {
                    return [
                        'id' => $firstItem->pelatihan_id,
                        'nama' => $firstItem->pelatihan->nama_pelatihan,
                        'keterangan' => $firstItem->pelatihan->keterangan,
                        'status' => $firstItem->pelatihan->status,
                        'type' => 'pelatihan',
                        'updated_at' => $groupedItems->max('updated_at'),
                    ];
                }
                return null; // Return null jika datanya tidak valid
            })->filter(); // Hapus item null dari koleksi

        // Gabungkan data sertifikasi dan pelatihan
        $data = $dataSertifikasi->values()->merge($dataPelatihan->values());

        // Urutkan berdasarkan updated_at
        $sortedData = $data->sortByDesc('updated_at')->values();

        Log::info('Data setelah sorting:', $sortedData->toArray());

        return DataTables::of(collect($sortedData))
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                if ($data['status'] === 'Proses' && $data['keterangan'] === 'Menunggu Validasi') {
                    // Tombol Validasi
                    $btn = '<button onclick="modalAction(\'' . url('/validasi/' . $data['type'] . '/' . $data['id'] . '/show_ajax2') . '\')" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Validasi
                        </button>';
                } else {
                    // Tombol Detail
                    $btn = '<button onclick="modalAction(\'' . url('/validasi/' . $data['type'] . '/' . $data['id'] . '/show_ajax') . '\')" class="btn btn-secondary btn-sm">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    //edit
    // public function show_ajax(string $type, string $id)
    // {
    //     if ($type === 'sertifikasi') {
    //         $data = DataSertifikasiModel::with('sertif', 'dosen')
    //             ->where('data_sertif_id', $id)
    //             ->first();
    //         if ($data) {
    //             return response()->json([
    //                 'status' => true,
    //                 'validasi' => [
    //                     'type' => 'sertifikasi',
    //                     'nama' => $data->sertif->nama_sertif,
    //                     'keterangan' => $data->keterangan,
    //                     'status' => $data->status,
    //                     'peserta' => $data->dosen->map(function ($d) {
    //                         return ['nama' => $d->nama];
    //                     })
    //                 ]
    //             ]);
    //         }
    //     } elseif ($type === 'pelatihan') {
    //         $data = DataPelatihanModel::with('pelatihan', 'dosen')
    //             ->where('data_pelatihan_id', $id)
    //             ->first();
    //         if ($data) {
    //             return response()->json([
    //                 'status' => true,
    //                 'validasi' => [
    //                     'type' => 'pelatihan',
    //                     'nama' => $data->pelatihan->nama_pelatihan,
    //                     'keterangan' => $data->keterangan,
    //                     'status' => $data->status,
    //                     'peserta' => $data->dosen->map(function ($d) {
    //                         return ['nama' => $d->nama];
    //                     })
    //                 ]
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Data tidak ditemukan'
    //     ]);
    // }
    public function show_ajax(string $type, string $id)
    {
        if ($type === 'sertifikasi') {
            // Ambil data sertifikasi dan relasi dengan dosen melalui tabel t_data_sertifikasi
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

            return view('validasi.pimpinan.show_ajax', [
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
        } else if ($type === 'pelatihan') {
            // Ambil data pelatihan dan relasi dengan dosen melalui tabel t_data_pelatihan
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


            return view('validasi.pimpinan.show_ajax', [
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

        // Jika tidak ada data yang ditemukan atau tipe tidak valid
        return view('validasi.pimpinan.show_ajax', [
            'error' => 'Data tidak ditemukan atau tipe tidak valid.',
        ]);
    }

    public function show_ajax2(string $type, string $id)
    {
        if ($type === 'sertifikasi') {
            // Ambil data sertifikasi dan relasi dengan dosen melalui tabel t_data_sertifikasi
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

            return view('validasi.pimpinan.show_ajax2', [
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
        } else if ($type === 'pelatihan') {
            // Ambil data pelatihan dan relasi dengan dosen melalui tabel t_data_pelatihan
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


            return view('validasi.pimpinan.show_ajax2', [
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

        // Jika tidak ada data yang ditemukan atau tipe tidak valid
        return view('validasi.pimpinan.show_ajax', [
            'error' => 'Data tidak ditemukan atau tipe tidak valid.',
        ]);
    }

    public function update_status(Request $request, string $type, string $id)
    {
        Log::info('Request Method:', ['method' => $request->method()]);
        Log::info('Request Data:', ['data' => $request->all()]);
        $status = $request->input('status');
        $keterangan = $status === 'approve' ? 'Validasi Disetujui' : 'Validasi Ditolak';

        if ($type === 'sertifikasi') {
            // Update data pada tabel SertifikasiModel
            SertifikasiModel::where('sertif_id', $id)->update(['keterangan' => $keterangan]);
        } elseif ($type === 'pelatihan') {
            // Update data pada tabel PelatihanModel
            PelatihanModel::where('pelatihan_id', $id)->update(['keterangan' => $keterangan]);
        } else {
            return response()->json(['error' => 'Tipe tidak valid'], 400);
        }

        return response()->json(['success' => 'Keterangan berhasil diperbarui.', 'keterangan' => $keterangan]);
    }
}
