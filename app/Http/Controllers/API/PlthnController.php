<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\LevelPelatihanModel;
use App\Models\VendorModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\PelatihanModel;
use App\Http\Controllers\Controller;
use App\Models\DataPelatihanModel;
use Illuminate\Support\Facades\DB;

class PlthnController extends Controller
{
    

public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:m_level_pelatihan,level_id',
            'bidang_id' => 'required|exists:m_bidang,bidang_id',
            'mk_id' => 'required|exists:m_matkul,mk_id',
            'vendor_id' => 'required|exists:m_vendor,vendor_id',
            'nama_pelatihan' => 'required|string|max:255',
            'biaya' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'kuota' => 'required|string|max:10',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:10',
            'status' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:50',
            'surat_tugas' => 'nullable|exists:m_surat_tugas,surat_tugas_id',
        ]);

        DB::beginTransaction();

        try {
            
            // Tambahkan data ke tabel t_pelatihan
            $pelatihan = PelatihanModel::create([
                'level_id' => $request->level_id,
                'bidang_id' => $request->bidang_id,
                'mk_id' => $request->mk_id,
                'vendor_id' => $request->vendor_id,
                'nama_pelatihan' => $request->nama_pelatihan,
                'biaya' => $request->biaya,
                'tanggal' => $request->tanggal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'kuota' => $request->kuota,
                'lokasi' => $request->lokasi,
                'periode' => $request->periode,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'surat_tugas' => $request->surat_tugas,
            ]);

            // Tambahkan data ke tabel t_data_pelatihan
            DataPelatihanModel::create([
                'pelatihan_id' => $pelatihan->pelatihan_id,
                'dosen_id' => $request->dosen_id,
                'surat_tugas_id' => $request->surat_tugas,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Data pelatihan berhasil ditambahkan.',
                'pelatihan' => $pelatihan,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function dropdown()
    {
        // Ambil data dropdown dan kembalikan dalam format JSON
        $levels = LevelPelatihanModel::all(); // Misalnya mengambil data dari model Level
        $vendors = VendorModel::all();
        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all();

        return response()->json([
            'levels' => $levels,
            'vendors' => $vendors,
            'bidangs' => $bidangs,
            'matkuls' => $matkuls
        ]);
    }
}