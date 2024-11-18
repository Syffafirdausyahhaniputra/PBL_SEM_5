<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;

class PelatihanController extends Controller
{
    public function index()
    {
        // Ambil data pelatihan beserta relasinya
        $pelatihans = PelatihanModel::with(['level', 'bidang', 'matkul', 'vendor'])->get();
        return view('pelatihan.index', compact('pelatihans'));
    }

    public function create()
    {
        // Ambil data dari tabel terkait untuk dropdown
        $levels = LevelPelatihanModel::all();
        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all();
        $vendors = VendorModel::all();

        return view('pelatihan.create', compact('levels', 'bidangs', 'matkuls', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required',
            'bidang_id' => 'required',
            'mk_id' => 'required',
            'vendor_id' => 'required',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
        ]);

        PelatihanModel::create($request->all());

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id);

        $levels = LevelPelatihanModel::all();
        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all();
        $vendors = VendorModel::all();

        return view('pelatihan.edit', compact('pelatihan', 'levels', 'bidangs', 'matkuls', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'level_id' => 'required',
            'bidang_id' => 'required',
            'mk_id' => 'required',
            'vendor_id' => 'required',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
        ]);

        $pelatihan = PelatihanModel::findOrFail($id);
        $pelatihan->update($request->all());

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelatihan = PelatihanModel::findOrFail($id);
        $pelatihan->delete();

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil dihapus.');
    }
}
