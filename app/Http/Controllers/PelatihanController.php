<?php

namespace App\Http\Controllers;

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
        $breadcrumb = (object) [
            'title' => 'Pelatihan',
            'subtitle' => 'Daftar Pelatihan' // Tambahkan jika subtitle diperlukan
        ];

        $pelatihan = PelatihanModel::all(); // Mengambil data pelatihan dari database

        return view('pelatihan.index', [
            'activeMenu' => 'pelatihan', // Menandai menu Pelatihan sebagai aktif
            'pelatihan' => $pelatihan,  // Mengirim data pelatihan ke view
            'breadcrumb' => $breadcrumb // Menyertakan breadcrumb ke view
        ]);
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
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        PelatihanModel::create($validatedData); // Menyimpan data ke database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan!');
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
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID
        $pelatihan->update($validatedData); // Memperbarui data pelatihan di database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui!');
    }

    // Method untuk menghapus data pelatihan
    public function destroy($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id); // Mengambil data pelatihan berdasarkan ID
        $pelatihan->delete(); // Menghapus data pelatihan dari database

        return redirect()->route('pelatihan.index')->with('success', 'Pelatihan berhasil dihapus!');
    }

    public function list(Request $request)
    {
        $dataPelatihan = DataPelatihanModel::with(['pelatihan', 'dosen'])->get();

        $response = [];
        foreach ($dataPelatihan as $item) {
            $response[] = [
                'data_pelatihan_id' => $item->data_pelatihan_id,
                'nama_pelatihan' => $item->pelatihan->nama_pelatihan ?? '-', // Mengambil nama pelatihan
                'nama_dosen' => $item->dosen->nama_dosen ?? '-', // Mengambil nama dosen
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'), // Format tanggal
            ];
        }

        return response()->json([
            'data' => $response // Respons JSON untuk DataTables
        ]);
    }


}
