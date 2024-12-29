<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiDosenController extends Controller
{
    /**
     * Menampilkan daftar notifikasi
     */
    public function list(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Ambil dosen_id dari user
        $dosenId = $user->dosen->dosen_id; // Pastikan relasi `dosen` ada di model User

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
                    'updated_at' => $item->updated_at,
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
                    'updated_at' => $item->updated_at,
                ];
            });

        // Konversi menjadi koleksi untuk mendukung fungsi merge()
        $dataSertifikasi = collect($dataSertifikasi);
        $dataPelatihan = collect($dataPelatihan);

        // Gabungkan dan urutkan data
        $data = $dataSertifikasi->merge($dataPelatihan)
            ->sortByDesc('updated_at')
            ->values();

        // Return data sebagai JSON
        return response()->json([
            'success' => true,
            'data' => $data,
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
            ->first(); // Gunakan first() untuk mendapatkan hanya satu data sertifikasi

        if (!$sertifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data sertifikasi tidak ditemukan.',
            ], 404);
        }

        // Ambil surat tugas terkait dengan sertifikasi (hanya satu surat tugas)
        $suratTugas = $sertifikasi->surat_tugas;

        // Persiapkan data surat tugas
        $suratTugasData = [
            'id' => $suratTugas->surat_tugas_id ?? null,
            'nama_surat_tugas' => $suratTugas->nama_surat ?? 'Tidak Diketahui',
            'file_url' => $suratTugas ? asset('dokumen/surat_tugas/' . $suratTugas->nama_surat) : null, // URL file surat tugas
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $sertifikasi->sertif->nama_sertif,
                'bidang' => $sertifikasi->sertif->bidang->bidang_nama,
                'matkul' => $sertifikasi->sertif->matkul->mk_nama,
                'vendor' => $sertifikasi->sertif->vendor->vendor_nama,
                'jenis' => $sertifikasi->sertif->jenis->jenis_nama,
                'tanggal_acara' => $sertifikasi->sertif->tanggal,
                'berlaku_hingga' => $sertifikasi->sertif->masa_berlaku,
                'periode' => $sertifikasi->sertif->periode,
                'keterangan' => $sertifikasi->sertif->keterangan,
                'surat_tugas' => $suratTugasData,
            ],
        ], 200);
    }

    public function showPelatihanApi($id)
    {
        // Ambil data pelatihan berdasarkan pelatihan_id
        $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
            ->where('pelatihan_id', $id)
            ->first(); // Gunakan first() untuk mendapatkan hanya satu data pelatihan

        if (!$pelatihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan.',
            ], 404);
        }

        // Ambil surat tugas terkait pelatihan (hanya satu surat tugas)
        $suratTugas = $pelatihan->surat_tugas;

        // Persiapkan data surat tugas
        $suratTugasData = [
            'id' => $suratTugas->surat_tugas_id ?? null,
            'nama_surat_tugas' => $suratTugas->nama_surat ?? 'Tidak Diketahui',
            'file_url' => $suratTugas ? asset('dokumen/surat_tugas/' . $suratTugas->nama_surat) : null, // URL file surat tugas
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $pelatihan->pelatihan->nama_pelatihan,
                'bidang' => $pelatihan->pelatihan->bidang->bidang_nama,
                'matkul' => $pelatihan->pelatihan->matkul->mk_nama,
                'vendor' => $pelatihan->pelatihan->vendor->vendor_nama,
                'level' => $pelatihan->pelatihan->level->level_nama,
                'tanggal_acara' => $pelatihan->pelatihan->tanggal,
                'berlaku_hingga' => $pelatihan->pelatihan->masa_berlaku,
                'periode' => $pelatihan->pelatihan->periode,
                'keterangan' => $pelatihan->pelatihan->keterangan,
                'surat_tugas' => $suratTugasData,
            ],
        ], 200);
    }
}
