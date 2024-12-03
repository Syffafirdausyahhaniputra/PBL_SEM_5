<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SertifikasiModel;
use App\Models\DataSertifikasiModel;
use App\Models\BidangModel;
use App\Models\JenisModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;

class InputSertifController extends Controller
{
    /**
     * Menampilkan daftar data sertifikasi.
     */
    public function list(Request $request)
    {
        try {
            // Mengambil data sertifikasi dengan relasi ke model terkait
            $sertifikasi = SertifikasiModel::with(['bidang', 'jenis', 'vendor', 'matkul'])->get();

            return response()->json([
                'status' => true,
                'message' => 'Data sertifikasi berhasil diambil',
                'data' => $sertifikasi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menyimpan data sertifikasi yang diterima dari Flutter.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_sertif' => 'required|string|max:255',
            'bidang_id' => 'required|integer|exists:bidang_models,id',
            'jenis_id' => 'required|integer|exists:jenis_models,id',
            'mk_id' => 'required|integer|exists:matkul_models,id',
            'vendor_id' => 'required|integer|exists:vendor_models,id',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi data gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Simpan data ke database
            $sertifikasi = SertifikasiModel::create([
                'nama_sertif' => $request->nama_sertif,
                'bidang_id' => $request->bidang_id,
                'jenis_id' => $request->jenis_id,
                'mk_id' => $request->mk_id,
                'vendor_id' => $request->vendor_id,
                'tanggal' => $request->tanggal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'biaya' => $request->biaya,
                'masa_berlaku' => $request->masa_berlaku,
                'periode' => $request->periode,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data sertifikasi berhasil disimpan',
                'data' => $sertifikasi,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
