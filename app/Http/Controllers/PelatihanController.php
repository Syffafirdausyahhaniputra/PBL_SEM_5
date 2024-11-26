<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataPelatihanModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PelatihanController extends Controller
{
    // Method untuk menampilkan daftar pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Pelatihan',
            'subtitle' => 'Daftar Pelatihan yang terdaftar dalam sistem' // Tambahkan jika subtitle diperlukan
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
    public function show_ajax(string $id)
    {
        // Ambil data DataPelatihan berdasarkan id
        $dataPelatihan = DataPelatihanModel::with([
            'pelatihan.level',
            'pelatihan.bidang',
            'pelatihan.matkul',
            'pelatihan.vendor',
        ])->find($id);

        // Periksa apakah data drowukan
        if ($dataPelatihan) {
            // Tampilkan halaman show_ajax dengan data pelatihan
            return view('pelatihan.show_ajax', ['dataPelatihan' => $dataPelatihan]);
        } else {
            // Tampilkan pesan kesalahan jika data tidak drowukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
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
        // Ambil data DataPelatihanModel beserta relasinya
        $dataPelatihan = DataPelatihanModel::select('pelatihan_id', 'status')
            ->groupBy('pelatihan_id', 'status')
            ->with('pelatihan');


        // Menggunakan DataTables untuk memproses data yang telah diambil
        return DataTables::of($dataPelatihan)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('nama_pelatihan', function ($row) {
                // Tampilkan nama pelatihan berdasarkan pelatihan_id
                return $row->pelatihan->nama_pelatihan ?? '-';
            })
            ->addColumn('nama_bidang', function ($row) {
                // Tampilkan nama bidang dari relasi pelatihan -> bidang
                return $row->pelatihan->bidang->bidang_nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                // Tampilkan tanggal pelatihan dari relasi pelatihan
                return $row->pelatihan->tanggal ?? '-';
            })
            ->addColumn('status', function ($row) {
                // Tampilkan tanggal pelatihan dari relasi pelatihan
                return $row->status ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                // Tombol aksi
                $btn  = '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->data_pelatihan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->data_pelatihan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->data_pelatihan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan HTML tombol dirender dengan benar
            ->make(true);
    }


    public function create_ajax()
    {
        // Ambil data tambahan jika diperlukan
        return view('pelatihan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|integer',
                'lokasi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Pelatihan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax($id)
    {
        $pelatihan = PelatihanModel::find($id);

        return view('pelatihan.edit_ajax', ['pelatihan' => $pelatihan]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|integer',
                'lokasi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->update($request->all());
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

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Pelatihan berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak drowukan'
            ]);
        }

        return redirect('/');
    }
}
