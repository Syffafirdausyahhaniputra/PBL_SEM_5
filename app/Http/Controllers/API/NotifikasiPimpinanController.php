<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Support\Facades\DB;

class NotifikasiPimpinanController extends Controller
{
    /**
     * Menampilkan daftar notifikasi
     */
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

        // Return data sebagai JSON
        return response()->json([
            'success' => true,
            'data' => $sortedData
        ], 200);
    }

    /**
     * Menampilkan detail notifikasi berdasarkan ID dan tipe
     */
    public function showSertifikasiApi($id)
    {
        // Ambil data sertifikasi berdasarkan sertif_id
        $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
            ->where('sertif_id', $id)
            ->get();

        if ($sertifikasi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data sertifikasi tidak ditemukan.',
            ], 404);
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

        return response()->json([
            'success' => true,
            'data' => [
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
            ],
        ], 200);
    }

    public function showPelatihanApi($id)
    {
        // Ambil data pelatihan berdasarkan pelatihan_id
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->where('pelatihan_id', $id)
            ->get();

        if ($pelatihan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan.',
            ], 404);
        }

        // Ambil data pertama untuk informasi umum pelatihan
        $firstItem = $pelatihan->first();

        $dosenList = $pelatihan->map(function ($item) {
            return [
                'id' => $item->dosen_id,
                'nama_dosen' => $item->dosen->user->nama ?? 'Tidak Diketahui', // Pastikan ada relasi dosen jika diperlukan
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
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
            ],
        ], 200);
    }

    /**
     * Verifikasi notifikasi
     */
    public function verify(Request $request, $type, $id)
    {
        $status = $request->input('status'); // 'Diterima' atau 'Ditolak'

        // Validasi input status
        if (!in_array($status, ['Diterima', 'Ditolak'])) {
            return response()->json(['message' => 'Status tidak valid.'], 400);
        }

        if ($type === 'pelatihan') {
            // Update semua data berdasarkan pelatihan_id
            $updated = PelatihanModel::where('pelatihan_id', $id)
                ->update([
                    'keterangan' => $status === 'Diterima' ? 'Validasi Diterima' : 'Validasi Ditolak',
                ]);
        } elseif ($type === 'Sertifikasi') {
            // Update semua data berdasarkan sertif_id
            $updated = SertifikasiModel::where('sertif_id', $id)
                ->update([
                    'keterangan' => $status === 'Diterima' ? 'Validasi Diterima' : 'Validasi Ditolak',
                ]);
        } else {
            return response()->json(['message' => 'Tipe notifikasi tidak valid.'], 400);
        }

        // Periksa apakah data berhasil diperbarui
        if ($updated === 0) {
            return response()->json(['message' => 'Tidak ada data yang diperbarui.'], 404);
        }

        return response()->json(['message' => 'Notifikasi berhasil diverifikasi.'], 200);
    }
}
