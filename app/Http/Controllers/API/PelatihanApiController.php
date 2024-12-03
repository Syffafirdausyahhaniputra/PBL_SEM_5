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
        $pelatihan = PelatihanModel::with(['level', 'bidang', 'matkul', 'vendor'])->get();

        return response()->json($pelatihan);
    }

    // POST: Menambahkan data pelatihan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:t_level,level_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
        ]);

        $pelatihan = PelatihanModel::create($validated);

        return response()->json($pelatihan, 201);
    }

    // GET: Menampilkan data pelatihan berdasarkan ID
    public function show($id)
    {
        $pelatihan = PelatihanModel::with(['level', 'bidang', 'matkul', 'vendor'])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $data = [
            'nama_pelatihan' => $pelatihan->nama_pelatihan,
            'tanggal' => $pelatihan->tanggal,
            'lokasi' => $pelatihan->lokasi,
            'periode' => $pelatihan->periode,
            'level' => $pelatihan->level->level_nama,
            'bidang' => $pelatihan->bidang->bidang_nama,
            'matkul' => $pelatihan->matkul->mk_nama,
            'vendor' => $pelatihan->vendor->vendor_nama,
        ];

        return response()->json($data);
    }

    // PUT/PATCH: Memperbarui data pelatihan
    public function update(Request $request, $id)
    {
        $pelatihan = PelatihanModel::find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $validated = $request->validate([
            'level_id' => 'required|exists:t_level,level_id',
            'bidang_id' => 'required|exists:t_bidang,bidang_id',
            'mk_id' => 'required|exists:t_matkul,mk_id',
            'vendor_id' => 'required|exists:t_vendor,vendor_id',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
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
