<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\LevelPelatihanModel;
use App\Models\VendorModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\PelatihanModel;
use App\Http\Controllers\Controller;


class PlthnController extends Controller
{
    // Mengambil data dropdown untuk form
    public function getDropdownOptions()
    {
        try {
            $levels = LevelPelatihanModel::all();
            $vendors = VendorModel::all();
            $bidangs = BidangModel::all();
            $matkuls = MatkulModel::all();

            return response()->json([
                'levels' => $levels,
                'vendors' => $vendors,
                'bidangs' => $bidangs,
                'matkuls' => $matkuls,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data dropdown',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menambahkan data pelatihan
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'level_id' => 'required|exists:level_pelatihan_models,id',
            'vendor_id' => 'required|exists:vendor_models,id',
            'bidang_id' => 'required|exists:bidang_models,id',
            'mk_id' => 'required|exists:matkul_models,id',
            'lokasi' => 'nullable|string|max:255',
            'kuota' => 'nullable|integer',
        ]);

        try {
            $pelatihan = PelatihanModel::create($validatedData);

            return response()->json([
                'message' => 'Pelatihan berhasil ditambahkan',
                'pelatihan' => $pelatihan,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan pelatihan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan daftar pelatihan
    public function index()
    {
        try {
            $pelatihan = PelatihanModel::with(['level', 'vendor', 'bidang', 'matkul'])->get();

            return response()->json([
                'data' => $pelatihan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data pelatihan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
