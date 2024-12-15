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
use Illuminate\Support\Facades\DB;

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
