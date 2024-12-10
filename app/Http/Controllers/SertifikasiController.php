<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\DataSertifikasiModel;
use App\Models\BidangModel;
use App\Models\DosenModel;
use App\Models\JenisModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Sertifikasi',
            'subtitle' => 'Daftar Sertifikasi yang terdaftar dalam sistem'
        ];

        $sertifikasi = SertifikasiModel::all();

        return view('sertifikasi.index', [
            'activeMenu' => 'sertifikasi',
            'sertifikasi' => $sertifikasi,
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function indexForDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Data Sertifikasi Dosen',
            'subtitle' => ' '
        ];

        // Mengambil data sertifikasi untuk dosen dengan relasi ke bidang dan jenis
        $sertifikasi = SertifikasiModel::with(['bidang', 'jenis'])->get();

        return view('sertifikasi.index_dosen', [
            'activeMenu' => 'sertifikasi_dosen',
            'sertifikasi' => $sertifikasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }


    public function create()
    {
        return view('sertifikasi.create', [
            'activeMenu' => 'sertifikasi',
        ]);
    }

    public function createForDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi Dosen',
            'subtitle' => 'Tambah Sertifikasi'
        ];

        $bidangs = BidangModel::all();
        $jenis = JenisModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor

        return view('sertifikasi.tambah_data', [
            'activeMenu' => 'sertifikasi_dosen',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
        ]);
    }



    public function show_ajax(string $id)
    {
        // Ambil data DataSertifikasi berdasarkan id

        $sertifikasi = SertifikasiModel::find((int) $id);
        $dataSertifikasi = DataSertifikasiModel::with([
            'sertifikasi.jenis',
            'sertifikasi.bidang',
            'sertifikasi.matkul',
            'sertifikasi.vendor',
        ])->find($id);

        // Periksa apakah data drowukan
        if ($sertifikasi !== null) {
            // Tampilkan halaman show_ajax dengan data sertifikasi
            return view('sertifikasi.show_ajax', ['dataSertifikasi' => $dataSertifikasi, 'sertifikasi' => $sertifikasi]);
        } else {
            // Tampilkan pesan kesalahan jika data tidak drowukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }


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

        $sertif_id = SertifikasiModel::create(attributes: $validatedData);

        DataSertifikasiModel::create([
            'sertif_id' => $sertif_id->sertif_id,
            'dosen_id' => auth()->user()->dosen->dosen_id,
            'status' => 'Pending'
        ]);

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil ditambahkan!');
    }

    public function storeForDosen(Request $request)
    {
        $validatedData = $request->validate([
            'nama_sertif' => 'required|string|max:255',
            'bidang_id' => 'required|integer',
            'jenis_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'masa_berlaku' => 'nullable|date',
            'periode' => 'required|string|max:50',
        ]);

        try {
            SertifikasiModel::create($validatedData);

            return redirect()->route('sertifikasi.dosen.index')
                ->with('success', 'Sertifikasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('sertifikasi.dosen.index')
                ->with('error', 'Terjadi kesalahan! Gagal menambahkan sertifikasi.');
        }
    }


    public function createtunjuk()
    {
        $breadcrumb = (object) [
            'title' => 'Sertifikasi Dosen',
            'subtitle' => 'Tambah Penunjukkan'
        ];

        $bidangs = BidangModel::all();
        $jenis = JenisModel::all();
        $matkuls = MatkulModel::all();
        $vendors = VendorModel::all();
        $dataS = DataSertifikasiModel::all();

        // Ambil data dosen beserta nama user
        $dataP = DosenModel::with('user')->get();

        return view('sertifikasi.createTunjuk', [
            'activeMenu' => 'sertifikasi',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            // 'levels' => $levels,
            'dataS' => $dataS,
        ]);
    }

    public function storeTunjuk(Request $request)
    {
        Log::info('Received request data:', $request->all());


        // Cek apakah request berupa AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $rules = [
                'dosen_id' => 'required|exists:m_dosen,dosen_id',
                'jenis_id' => 'required|integer',
                'bidang_id' => 'required|integer',
                'mk_id' => 'required|integer',
                'vendor_id' => 'required|integer',
                'nama_sertifikasi' => 'required|string|max:255',
                'tanggal' => 'required|date',
                // 'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
                // 'lokasi' => 'required|string|max:255',
                'periode' => 'required|string|max:50',
                'kuota' => 'required|integer',
                'biaya' => 'required|numeric|min:0',
                'masa_berlaku' => 'nullable|date',


                // 'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048', // Nullable file field
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // Pesan error validasi
                ]);
            }

            try {

                // Create Sertifikasi 
                $sertifikasi = SertifikasiModel::create($request->except('file')); // Save all fields except 'file'

                // Log the created model
                Log::info('Sertifikasi created:', $sertifikasi->toArray());

                // Create DataSertifikasi record
                DataSertifikasiModel::create([
                    'sertifikasi_id' => $sertifikasi->sertifikasi_id, // ID sertifikasi yang baru saja disimpan
                    'dosen_id' => $request->input('dosen_id', null), // Sesuaikan input dosen_id
                    'keterangan' => 'Menunggu validasi', // Atur keterangan default
                    'status' => 'Proses', // Atur status default
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Sertifikasi berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                Log::error('Error saving sertifikasi:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all()
                ]);

                $errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                    $errorMessage .= ': Kesalahan database';
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Jika bukan AJAX, redirect ke halaman utama
        return redirect('/sertifikasi');
    }

    public function edit($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id);

        return view('sertifikasi.edit', [
            'activeMenu' => 'sertifikasi',
            'sertifikasi' => $sertifikasi,
        ]);
    }

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

    public function destroy($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id);
        $sertifikasi->delete();

        return redirect()->route('sertifikasi.index')->with('success', 'Sertifikasi berhasil dihapus!');
    }

    public function list(Request $request)
    {
        $dataSertifikasi = DataSertifikasiModel::select('sertif_id', 'status')
            ->groupBy('sertif_id', 'status')
            ->with('sertifikasi');

        return DataTables::of($dataSertifikasi)
            ->addIndexColumn()
            ->addColumn('nama_sertifikasi', function ($row) {
                return $row->sertifikasi->nama_sertif ?? '-';
            })
            ->addColumn('nama_bidang', function ($row) {
                return $row->sertifikasi->bidang->bidang_nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->sertifikasi->tanggal ?? '-';
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/sertifikasi/' . $row->sertif_id . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $row->sertif_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $row->sertifikasi->sertif_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('sertifikasi.create_ajax');
    }

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

    public function edit_ajax($id)
    {
        $sertifikasi = SertifikasiModel::find($id);

        return view('sertifikasi.edit_ajax', ['sertifikasi' => $sertifikasi]);
    }

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