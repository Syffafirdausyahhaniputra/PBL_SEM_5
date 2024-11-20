<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SertifikasiModel; // Import model yang sudah ada
use App\Models\LevelSertifikasiModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataSertifikasiModel;

class SertifikasiController extends Controller
{
    // Method untuk menampilkan daftar sertifikasi
    public function index()
{
    try {
        $sertifikasi = SertifikasiModel::all(); // Ambil semua data sertifikasi

        return response()->json([
            'success' => true,
            'data' => $sertifikasi
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching sertifikasi: ' . $e->getMessage()
        ], 500);
    }
}


    // Method untuk menampilkan form tambah sertifikasi
    public function create()
    {
        return view('sertifikasi.create', [
            'activeMenu' => 'sertifikasi', // Menandai menu sertifikasi sebagai aktif
        ]);
    }

    // Method untuk menyimpan data sertifikasi baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        SertifikasiModel::create($validatedData); // Menyimpan data ke database

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil ditambahkan!');
    }

    // Method untuk menampilkan form edit pelatihan
    public function edit($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id); // Mengambil data sertifikasi berdasarkan ID

        return view('sertifikasi.edit', [
            'activeMenu' => 'sertifikasi', // Menandai menu Sertifikasi sebagai aktif
            'sertifikasi' => $sertifikasi, // Mengirim data sertifikasi ke view
        ]);
    }

    // Method untuk memperbarui data pelatihan
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        $sertifikasi = SertifikasiModel::findOrFail($id); // Mengambil data sertifikasi berdasarkan ID
        $sertifikasi->update($validatedData); // Memperbarui data sertifikasi di database

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil diperbarui!');
    }

    // Method untuk menghapus data sertifikasi
    public function destroy($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id); // Mengambil data sertifikasi berdasarkan ID
        $sertifikasi->delete(); // Menghapus data sertifikasi dari database

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil dihapus!');
    }

    public function list(Request $request)
{
    try {
        $dataSertifikasi = DataSertifikasiModel::with(['sertifikasi', 'dosen'])->get();

        $response = $dataSertifikasi->map(function ($item) {
            return [
                'data_sertifikasi_id' => $item->id,
                'nama_sertifikasi' => $item->sertifikasi->nama_sertifikasi ?? '-', // Pastikan relasi ada
                'nama_dosen' => $item->dosen->nama_dosen ?? '-', // Pastikan relasi ada
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $response
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching data: ' . $e->getMessage()
        ], 500);
    }
}

}
