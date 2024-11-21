<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\DataSertifikasiModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use Illuminate\Support\Facades\Validator;

class SertifikasiController extends Controller
{
    // Menampilkan daftar sertifikasi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi',
            'subtitle' => 'Daftar Sertifikasi'
        ];

        $sertifikasi = SertifikasiModel::with(['jenis', 'bidang', 'matkul', 'vendor'])->get();

        return view('sertifikasi.index', [
            'activeMenu' => 'sertifikasi',
            'sertifikasi' => $sertifikasi,
            'breadcrumb' => $breadcrumb
        ]);
    }

    // Menampilkan form tambah sertifikasi
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi',
            'subtitle' => 'Tambah Sertifikasi'
        ];

        return view('sertifikasi.create', [
            'activeMenu' => 'sertifikasi',
            'breadcrumb' => $breadcrumb
        ]);
    }

    // Menyimpan sertifikasi baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'nama_sertif' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'nullable|string|max:50',
        ]);

        SertifikasiModel::create($validatedData);

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil ditambahkan!');
    }

    // Menampilkan form edit sertifikasi
    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi',
            'subtitle' => 'Edit Sertifikasi'
        ];

        $sertifikasi = SertifikasiModel::findOrFail($id);

        return view('sertifikasi.edit', [
            'activeMenu' => 'sertifikasi',
            'sertifikasi' => $sertifikasi,
            'breadcrumb' => $breadcrumb
        ]);
    }

    // Memperbarui data sertifikasi
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jenis_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'nama_sertif' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'nullable|string|max:50',
        ]);

        $sertifikasi = SertifikasiModel::findOrFail($id);
        $sertifikasi->update($validatedData);

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil diperbarui!');
    }

    // Menghapus sertifikasi
    public function destroy($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id);
        $sertifikasi->delete();

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil dihapus!');
    }

    // Menampilkan daftar data sertifikasi untuk DataTables
    public function list(Request $request)
    {
        $query = DataSertifikasiModel::with(['sertif', 'dosen']);

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->whereHas('sertif', function ($q) use ($search) {
                $q->where('nama_sertif', 'like', "%$search%");
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
                'data_sertifikasi_id' => $item->data_sertif_id,
                'nama_sertifikasi' => $item->sertif->nama_sertif ?? '-',
                'nama_dosen' => $item->dosen->nama ?? '-',
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

    public function show_ajax($id)
    {
        $data = DataSertifikasiModel::with([
            'sertif.jenis',
            'sertif.bidang',
            'sertif.matkul',
            'sertif.vendor',
            'dosen'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $response = [
            'nama_sertifikasi' => $data->sertif->nama_sertif ?? null,
            'bidang_nama' => $data->sertif->bidang->nama_bidang ?? null,
            'matkul' => $data->sertif->matkul->nama_matkul ?? null,
            'tanggal' => $data->sertif->tanggal ?? null,
            'masa_berlaku' => $data->sertif->masa_berlaku ?? null,
            'vendor_nama' => $data->sertif->vendor->nama_vendor ?? null,
            'jenis' => $data->sertif->jenis->nama_jenis ?? null,
            'periode' => $data->sertif->periode ?? null,
            'dosen_nama' => $data->dosen->nama ?? null
        ];

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    // Menyimpan data sertifikasi melalui AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_id' => 'required|integer',
                'bidang_id' => 'required|integer',
                'mk_id' => 'required|integer',
                'vendor_id' => 'required|integer',
                'nama_sertif' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'masa_berlaku' => 'nullable|date',
                'periode' => 'nullable|string|max:50',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            SertifikasiModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Sertifikasi berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan data untuk form edit melalui AJAX
    public function edit_ajax($id)
    {
        $sertifikasi = SertifikasiModel::find($id);

        return view('sertifikasi.edit_ajax', ['sertifikasi' => $sertifikasi]);
    }

    // Memperbarui data sertifikasi melalui AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_id' => 'required|integer',
                'bidang_id' => 'required|integer',
                'mk_id' => 'required|integer',
                'vendor_id' => 'required|integer',
                'nama_sertif' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'masa_berlaku' => 'nullable|date',
                'periode' => 'nullable|string|max:50',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                $sertifikasi->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    // Menghapus data sertifikasi melalui AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                $sertifikasi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Sertifikasi berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }
}