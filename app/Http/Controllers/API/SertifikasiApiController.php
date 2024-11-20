<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SertifikasiModel;
use Carbon\Carbon;
use DB;


class SertifikasiApiController extends Controller
{
    // GET: Menampilkan semua data sertifikasi
    public function index()
    {
        $sertifikasi = SertifikasiModel::with(['jenis', 'bidang', 'matkul', 'vendor'])->get();

        return response()->json($sertifikasi);
    }

    // POST: Menambahkan data sertifikasi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_id' => 'required|exists:t_jenis,jenis_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_sertif' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        $sertifikasi = SertifikasiModel::create($validated);

        return response()->json($sertifikasi, 201);
    }

    // GET: Menampilkan data sertifikasi berdasarkan ID
    public function show($id)
    {
        $sertifikasi = SertifikasiModel::with(['jenis', 'bidang', 'matkul', 'vendor'])->find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json($sertifikasi);
    }

    // PUT/PATCH: Memperbarui data sertifikasi
    public function update(Request $request, $id)
    {
        $sertifikasi = SertifikasiModel::find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $validated = $request->validate([
            'jenis_id' => 'required|exists:t_jenis,jenis_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_sertif' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        $sertifikasi->update($validated);

        return response()->json($sertifikasi);
    }

    // DELETE: Menghapus data sertifikasi
    public function destroy($id)
    {
        $sertifikasi = SertifikasiModel::find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $sertifikasi->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
