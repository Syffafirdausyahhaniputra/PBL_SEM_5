<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use Illuminate\Support\Facades\DB;

class NotifikasiPimpinanController extends Controller
{
    /**
     * Menampilkan daftar notifikasi
     */
    public function list()
    {
        // Mengambil notifikasi dengan kriteria keterangan 'Verifikasi Pimpinan' dan status 'Proses'
        $pelatihan = DataPelatihanModel::with('pelatihan')
            ->where('status', 'Proses')
            ->where('keterangan', 'Verifikasi Pimpinan')
            ->get();

        $sertifikasi = DataSertifikasiModel::with('sertifikasi')
            ->where('status', 'Proses')
            ->where('keterangan', 'Verifikasi Pimpinan')
            ->get();

        // Menggabungkan data pelatihan dan sertifikasi
        $notifikasi = $pelatihan->map(function ($item) {
            return [
                'id' => $item->pelatihan_id,
                'type' => 'Pelatihan',
                'nama' => $item->pelatihan->nama_pelatihan,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
            ];
        })->merge(
            $sertifikasi->map(function ($item) {
                return [
                    'id' => $item->sertif_id,
                    'type' => 'Sertifikasi',
                    'nama' => $item->sertifikasi->nama_sertif,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                ];
            })
        );

        // Menghapus duplikasi berdasarkan ID
        $notifikasiUnik = $notifikasi->unique(function ($item) {
            return $item['id'] . $item['type']; // Kombinasi ID dan tipe untuk memastikan unik
        })->values(); // Reset indeks koleksi

        return response()->json($notifikasiUnik, 200);
    }

    /**
     * Menampilkan detail notifikasi berdasarkan ID dan tipe
     */
    public function show(Request $request, $type, $id)
    {
        if ($type === 'Pelatihan') {
            $data = DataPelatihanModel::with(['pelatihan.level', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'dosen.user'])
                ->where('pelatihan_id', $id)
                ->first();
            $dosenNama = DB::table('t_data_pelatihan')
                ->join('m_dosen', 't_data_pelatihan.dosen_id', '=', 'm_dosen.dosen_id')
                ->join('m_user', 'm_dosen.user_id', '=', 'm_user.user_id')
                ->where('t_data_pelatihan.pelatihan_id', $id)
                ->pluck('m_user.nama');

            if (!$data) {
                return response()->json(['message' => 'Notifikasi tidak ditemukan.'], 404);
            }

            $detail = [
                'Nama Pelatihan' => $data->pelatihan->nama_pelatihan,
                'Level Pelatihan' => $data->pelatihan->level->level_nama ?? null,
                'Bidang' => $data->pelatihan->bidang->bidang_nama ?? null,
                'Mata Kuliah' => $data->pelatihan->matkul->mk_nama ?? null,
                'Vendor' => $data->pelatihan->vendor->vendor_nama ?? null,
                'Tanggal' => $data->pelatihan->tanggal,
                'Tanggal Akhir' => $data->pelatihan->tanggal_akhir,
                'Kuota' => $data->pelatihan->kuota,
                'Biaya' => $data->pelatihan->biaya,
                'Lokasi' => $data->pelatihan->lokasi,
                'Periode' => $data->pelatihan->periode,
                'Dosen' => $dosenNama,
            ];
        } elseif ($type === 'Sertifikasi') {
            $data = DataSertifikasiModel::with(['sertifikasi.jenis', 'sertifikasi.bidang', 'sertifikasi.matkul', 'sertifikasi.vendor', 'dosen.user'])
                ->where('sertif_id', $id)
                ->first();

            $dosenNama = DB::table('t_data_sertifikasi')
                ->join('m_dosen', 't_data_sertifikasi.dosen_id', '=', 'm_dosen.dosen_id')
                ->join('m_user', 'm_dosen.user_id', '=', 'm_user.user_id')
                ->where('t_data_sertifikasi.sertif_id', $id)
                ->pluck('m_user.nama');

            if (!$data) {
                return response()->json(['message' => 'Notifikasi tidak ditemukan.'], 404);
            }

            $detail = [
                'Nama Sertifikasi' => $data->sertifikasi->nama_sertif,
                'Jenis Sertifikasi' => $data->sertifikasi->jenis->jenis_nama ?? null,
                'Bidang' => $data->sertifikasi->bidang->bidang_nama ?? null,
                'Mata Kuliah' => $data->sertifikasi->matkul->mk_nama ?? null,
                'Vendor' => $data->sertifikasi->vendor->vendor_nama ?? null,
                'Tanggal' => $data->sertifikasi->tanggal,
                'Tanggal Akhir' => $data->sertifikasi->tanggal_akhir,
                'Biaya' => $data->sertifikasi->biaya,
                'Periode' => $data->sertifikasi->periode,
                'Dosen' => $dosenNama,
            ];
        } else {
            return response()->json(['message' => 'Tipe notifikasi tidak valid.'], 400);
        }

        return response()->json($detail, 200);
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

        if ($type === 'Pelatihan') {
            // Update semua data berdasarkan pelatihan_id
            $updated = DataPelatihanModel::where('pelatihan_id', $id)
                ->update([
                    'status' => $status,
                    'keterangan' => $status === 'Diterima' ? 'Verifikasi Pimpinan Diterima' : 'Verifikasi Pimpinan Ditolak',
                ]);
        } elseif ($type === 'Sertifikasi') {
            // Update semua data berdasarkan sertif_id
            $updated = DataSertifikasiModel::where('sertif_id', $id)
                ->update([
                    'status' => $status,
                    'keterangan' => $status === 'Diterima' ? 'Verifikasi Pimpinan Diterima' : 'Verifikasi Pimpinan Ditolak',
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
