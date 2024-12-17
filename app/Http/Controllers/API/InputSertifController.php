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
use Illuminate\Support\Facades\DB;

class InputSertifController extends Controller
{

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
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
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

        DB::beginTransaction();

        try {
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

            DataSertifikasiModel::create([
                'sertifikasi_id' => $sertifikasi->sertifikasi_id,
                'dosen_id' => $request->dosen_id,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data sertifikasi berhasil disimpan',
                'data' => $sertifikasi,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil data dropdown untuk form.
     */
    public function dropdown()
    {
        try {
            $jeniss = JenisModel::all();
            $vendors = VendorModel::all();
            $bidangs = BidangModel::all();
            $matkuls = MatkulModel::all();

            return response()->json([
                'status' => true,
                'message' => 'Data dropdown berhasil diambil',
                'data' => [
                    'jenis' => $jeniss,
                    'vendors' => $vendors,
                    'bidangs' => $bidangs,
                    'matkuls' => $matkuls,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data dropdown',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
