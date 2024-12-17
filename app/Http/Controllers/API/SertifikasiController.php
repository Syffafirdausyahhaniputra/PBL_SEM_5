<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SertifikasiModel; // Import model yang sudah ada
use App\Models\JenisModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataSertifikasiModel;
use Illuminate\Support\Facades\DB;

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

    // Method untuk menyimpan data pelatihan baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required|exists:m_jenis,jenis_id',
            'bidang_id' => 'required|exists:m_bidang,bidang_id',
            'mk_id' => 'required|exists:m_matkul,mk_id',
            'vendor_id' => 'required|exists:m_vendor,vendor_id',
            'nama_sertifikasi' => 'required|string|max:255',
            'biaya' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:10',
            'status' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:50',
            'surat_tugas' => 'nullable|exists:m_surat_tugas,surat_tugas_id',
        ]);

        DB::beginTransaction();

        try {
            $sertifikasi = SertifikasiModel::create([
                'jenis_id' => $request->jenis_id,
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
            DataSertifikasiModel::create([
                'sertifikasi_id' => $sertifikasi->pelatihan_id,
                'dosen_id' => $request->dosen_id,
                'surat_tugas_id' => $request->surat_tugas,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Data sertifikasi berhasil ditambahkan.',
                'pelatihan' => $sertifikasi,
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
        $Jenis   = JenisModel::all(); // Misalnya mengambil data dari model Level
        $vendors = VendorModel::all();
        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all();

        return response()->json([
            'jenis'   => $Jenis,
            'vendors' => $vendors,
            'bidangs' => $bidangs,
            'matkuls' => $matkuls
        ]);
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
            'jenis_id' => 'required|exists:jenis,jenis_id',
            'bidang_id' => 'required|exists:bidang,bidang_id',
            'mk_id' => 'nullable|exists:matkul,mk_id',
            'vendor_id' => 'nullable|exists:vendor,vendor_id',
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
