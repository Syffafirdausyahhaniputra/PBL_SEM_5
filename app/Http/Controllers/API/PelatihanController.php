<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataPelatihanModel;

class PelatihanController extends Controller
{
    // Method untuk menampilkan daftar pelatihan
    public function index()
{
    try {
        $pelatihan = PelatihanModel::all(); // Ambil semua data pelatihan

        return response()->json([
            'success' => true,
            'data' => $pelatihan
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching pelatihan: ' . $e->getMessage()
        ], 500);
    }
}


    // Method untuk menampilkan form tambah pelatihan
    public function create()
    {
        return view('pelatihan.create', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
        ]);
    }

    // Method untuk menyimpan data pelatihan baru
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nama_pelatihan' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'kuota' => 'required|integer|min:1',
        'lokasi' => 'required|string|max:255',
        'level_id' => 'required|exists:level_pelatihan,level_id',
        'bidang_id' => 'required|exists:bidang,bidang_id',
        'mk_id' => 'nullable|exists:matkul,mk_id',
        'vendor_id' => 'nullable|exists:vendor,vendor_id',
    ]);

    try {
        PelatihanModel::create($validatedData);
        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->route('pelatihan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    // Method untuk menampilkan form edit pelatihan
    public function edit($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID

        return view('pelatihan.edit', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
            'pelatihan' => $pelatihan, // Mengirim data pelatihan ke view
        ]);
    }

    // Method untuk memperbarui data pelatihan
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'nama_pelatihan' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'kuota' => 'required|integer|min:1',
        'lokasi' => 'required|string|max:255',
        'level_id' => 'required|exists:level_pelatihan,level_id',
        'bidang_id' => 'required|exists:bidang,bidang_id',
        'mk_id' => 'nullable|exists:matkul,mk_id',
        'vendor_id' => 'nullable|exists:vendor,vendor_id',
    ]);

    $pelatihan = PelatihanModel::findOrFail($id);

    try {
        $pelatihan->update($validatedData);
        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->route('pelatihan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    // Method untuk menghapus data pelatihan
    public function destroy($id)
{
    $pelatihan = PelatihanModel::findOrFail($id);

    try {
        $pelatihan->delete();
        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->route('pelatihan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function list(Request $request)
{
    try {
        $dataPelatihan = DataPelatihanModel::with(['pelatihan', 'dosen'])->get();

        $response = $dataPelatihan->map(function ($item) {
            return [
                'data_pelatihan_id' => $item->id,
                'nama_pelatihan' => $item->pelatihan->nama_pelatihan ?? '-', // Relasi pelatihan
                'nama_dosen' => $item->dosen->nama_dosen ?? '-',             // Relasi dosen
                'Bidang' => $item->pelatihan->bidang->bidang_nama ?? '-',             // Relasi bidang
                'tanggal' => $item->pelatihan->tanggal ?? '-',             // Relasi bidang
                'status' => $item->status ?? '-',
                'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-',
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
