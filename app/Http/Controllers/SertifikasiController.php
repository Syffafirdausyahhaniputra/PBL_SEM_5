<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikasiModel; // Import model yang sudah ada
use App\Models\LevelSertifikasiModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataSertifikasiModel;

class SertifikasiController extends Controller
{
    // Method untuk menampilkan daftar pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi',
            'subtitle' => 'Daftar Pelatihan' // Tambahkan jika subtitle diperlukan
        ];

        $sertifikasi = SertifikasiModel::all(); // Mengambil data sertifikasi dari database

        return view('sertifikasi.index', [
            'activeMenu' => 'sertifikasi', // Menandai menu sertifikasi sebagai aktif
            'sertifikasi' => $sertifikasi,  // Mengirim data sertifikasi ke view
            'breadcrumb' => $breadcrumb // Menyertakan breadcrumb ke view
        ]);
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
            'activeMenu' => 'sertifikasi', // Menandai menu sertifikasi sebagai aktif
            'sertifikasi' => $sertifikasi, // Mengirim data sertifikasi ke view
        ]);
    }

    // Method untuk memperbarui data sertifikasi
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
        ]);

        $sertifikasi = SertifikasiModel::findOrFail($id); // Mengambil data sertifikasi berdasarkan ID
        $sertifikasi->update($validatedData); // Memperbarui data pelatihan di database

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil diperbarui!');
    }

    // Method untuk menghapus data pelatihan
    public function destroy($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id); // Mengambil data sertifikasi berdasarkan ID
        $sertifikasi->delete(); // Menghapus data pelatihan dari database

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil dihapus!');
    }

    public function list(Request $request)
{
    $query = DataSertifikasiModel::with(['sertifikasi', 'dosen']);

    // Filter, Search, dan Pagination
    if ($search = $request->input('search.value')) {
        $query->whereHas('sertifikasi', function ($q) use ($search) {
            $q->where('nama_sertifikasi', 'like', "%$search%");
        });
    }

    $recordsTotal = $query->count();

    $dataSertifikasi = $query
        ->offset($request->start)
        ->limit($request->length)
        ->get();

    // Format data untuk DataTables
    $response = $dataSertifikasi->map(function ($item) {
        return [
            'data_sertifikasi_id' => $item->id,
            'nama_sertifikasi' => $item->sertifikasi->nama_sertifikasi ?? '-',
            'nama_dosen' => $item->dosen->nama_dosen ?? '-',
            'status' => $item->status ?? '-',
            'created_at' => $item->created_at->format('Y-m-d H:i:s'),
        ];
    });

    return response()->json([
        'draw' => $request->input('draw'),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsTotal,
        'data' => $response
    ]);
}


}
