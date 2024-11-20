<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PelatihanModel;
use Carbon\Carbon;
use DB;


class PelatihanApiController extends Controller
{
    // GET: Menampilkan semua data pelatihan
    public function index()
    {
        $pelatihan = PelatihanModel::with(['jenis', 'bidang', 'matkul', 'vendor'])->get();

        return response()->json($pelatihan);
    }

    // POST: Menambahkan data pelatihan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_id' => 'required|exists:t_jenis,jenis_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        $pelatihan = PelatihanModel::create($validated);

        return response()->json($pelatihan, 201);
    }

    // GET: Menampilkan data pelatihan berdasarkan ID
    public function show($id)
    {
        $pelatihan = PelatihanModel::with(['jenis', 'bidang', 'matkul', 'vendor'])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json($pelatihan);
    }

    // PUT/PATCH: Memperbarui data pelatihan
    public function update(Request $request, $id)
    {
        $pelatihan = PelatihanModel::find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $validated = $request->validate([
            'jenis_id' => 'required|exists:t_jenis,jenis_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        $pelatihan->update($validated);

        return response()->json($pelatihan);
    }

    // DELETE: Menghapus data pelatihan
    public function destroy($id)
    {
        $pelatihan = PelatihanModel::find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $pelatihan->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
