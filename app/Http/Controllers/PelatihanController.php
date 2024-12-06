<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataPelatihanModel;
use App\Models\JenisModel;
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

    public function indexForDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Data Pelatihan Dosen',
            'subtitle' => ' '
        ];

        // Mengambil data sertifikasi dengan relasi ke bidang dan jenis
        $pelatihan = PelatihanModel::with(['bidang', 'vendor'])->get();

        return view('pelatihan.index_dosen', [
            'activeMenu' => 'pelatihan_dosen',
            'pelatihan' => $pelatihan,
            'breadcrumb' => $breadcrumb,
        ]);
    }

  
    public function createForDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Pelatihan Dosen',
            'subtitle' => 'Tambah Pelatihan'
        ];

        $bidangs = BidangModel::all();
        $jenis = JenisModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor
        $levels = LevelPelatihanModel::all();

        return view('pelatihan.tambah_data', [
            'activeMenu' => 'pelatihan_dosen',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
        ]);
        
    }

    public function show_ajax(string $id)
    {
        // Ambil data DataPelatihan berdasarkan id
        
        $pelatihan = PelatihanModel::find((int) $id);
        $dataPelatihan = DataPelatihanModel::with([
            'pelatihan.level',
            'pelatihan.bidang',
            'pelatihan.matkul',
            'pelatihan.vendor',
        ])->find($id);
        
        // Periksa apakah data drowukan
        if ($pelatihan !== null) {
            // Tampilkan halaman show_ajax dengan data pelatihan
            return view('pelatihan.show_ajax', ['dataPelatihan' => $dataPelatihan,'pelatihan'=>$pelatihan]);
        } else {
            // Tampilkan pesan kesalahan jika data tidak drowukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function storeForDosen(Request $request)
    {
        $validatedData = $request->validate([
            'level_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'biaya' => 'required|numeric|min:0',
        ]);

        try {
            PelatihanModel::create($validatedData);

            return redirect()->route('pelatihan.dosen.index')
                ->with('success', 'Pelatihan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('pelatihan.dosen.index')
                ->with('error', 'Terjadi kesalahan! Gagal menambahkan pelatihan.');
        }
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

        $hostname = $request->getHost();


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
                $btn  = '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan_id) . '/show_ajax' . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan->pelatihan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan HTML tombol dirender dengan benar
            ->make(true);
    }


    public function create_ajax()
    {
        $breadcrumb = (object) [
            'title' => 'Pelatihan Dosen',
            'subtitle' => 'Tambah Pelatihan'
        ];

        $bidangs = BidangModel::all();
        $jenis = JenisModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor
        $levels = LevelPelatihanModel::all();

        return view('pelatihan.create_ajax', [
            'activeMenu' => 'pelatihan',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
        ]);
        
    }

    public function store_ajax(Request $request)
    {
        $validatedData = $request->validate([
            'level_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'kuota' => 'required|integer',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'biaya' => 'required|numeric|min:0',
        ]);

        try {
            PelatihanModel::create($validatedData);

            return redirect()->route('pelatihan.index')
                ->with('success', 'Pelatihan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('pelatihan.index')
                ->with('error', 'Terjadi kesalahan! Gagal menambahkan pelatihan.');
        }
    }

    public function edit_ajax($id)
    {
        // Mencari data pelatihan berdasarkan ID
        $pelatihan = PelatihanModel::find($id);

        // Jika pelatihan tidak ditemukan
        if (!$pelatihan) {
        return response()->json([
            'status' => false,
            'message' => 'Pelatihan tidak ditemukan'
        ]);
    }

        // Mengembalikan tampilan dengan data pelatihan
        return view('pelatihan.edit_ajax', ['pelatihan' => $pelatihan]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'bidang_id' => 'required|integer',
                'mk_id' => 'required|integer',
                'vendor_id' => 'required|integer',
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
                'kuota' => 'required|integer',
                'lokasi' => 'required|string|max:255',
                'periode' => 'required|string|max:50',
                'biaya' => 'required|numeric|min:0',
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

    public function confirm_ajax($id)
{
    // Cari data pelatihan berdasarkan ID
    $pelatihan = PelatihanModel::find($id);

    // Jika pelatihan tidak ditemukan
    if (!$pelatihan) {
        return response()->json([
            'status' => false,
            'message' => 'Pelatihan tidak ditemukan'
        ]);
    }

    // Mengembalikan tampilan konfirmasi untuk menghapus
    return view('pelatihan.confirm_delete', ['pelatihan' => $pelatihan]);
}

public function delete_ajax(Request $request, $id)
{
    $pelatihan = PelatihanModel::find($id);

    if ($pelatihan) {
        try {
            $pelatihan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Pelatihan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data!'
            ]);
        }
    }

    return response()->json([
        'status' => false,
        'message' => 'Data tidak ditemukan!'
    ]);
}

    public function showPelatihan($id)
{
    // Cari pelatihan berdasarkan id
    dd($id);
    $pelatihan = PelatihanModel::find($id);

    // Periksa apakah pelatihan ditemukan
    if ($pelatihan) {
        // Tampilkan halaman show_ajax dengan data pelatihan
        return view('pelatihan.show_ajax', ['pelatihan' => $pelatihan]);
    } else {
        // Tampilkan pesan kesalahan jika pelatihan tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Data pelatihan tidak ditemukan'
        ]);
    }
}

}
