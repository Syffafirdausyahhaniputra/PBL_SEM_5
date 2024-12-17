<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel; // Import model yang sudah ada
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use App\Models\DataPelatihanModel;
use App\Models\DosenModel;
use App\Models\GolonganModel;
use App\Models\JabatanModel;
use App\Models\JenisModel;
use App\Models\SuratTugasModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
// use App\Http\Controllers\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;


class PelatihanController extends Controller
{
    // Method untuk menampilkan daftar pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Pelatihan',
            'subtitle' => 'Daftar Pelatihan yang terdaftar dalam sistem' // Tambahkan jika subtitle diperlukan
        ];

        $pelatihan = PelatihanModel::with(['bidang', 'vendor'])->get(); // Mengambil data pelatihan dari database

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

    public function createtunjuk()
    {
        $breadcrumb = (object) [
            'title' => 'Pelatihan Dosen',
            'subtitle' => 'Tambah Pelatihan'
        ];

        $bidangs = BidangModel::all();
        $jenis = JenisModel::all();
        $matkuls = MatkulModel::all();
        $vendors = VendorModel::all();
        $levels = LevelPelatihanModel::all();

        // Ambil bidang_id dan mk_id dari request (jika ada)
        $bidangId = request('bidang_id');
        $matkulId = request('mk_id');

        // Ambil data dosen beserta nama user
        $dataP = DosenModel::with('user')
            ->leftJoin('t_data_sertifikasi', 'm_dosen.dosen_id', '=', 't_data_sertifikasi.dosen_id')
            ->leftJoin('t_sertifikasi', 't_data_sertifikasi.sertif_id', '=', 't_sertifikasi.sertif_id')
            ->select('m_dosen.*')  // Select m_dosen columns
            ->distinct()  // Ensures no duplicates for dosen_id
            ->selectRaw("CASE WHEN t_sertifikasi.status = 'Proses' THEN 1 ELSE 0 END as is_in_process")
            ->selectRaw("EXISTS (
            SELECT 1 
            FROM m_dosen_bidang 
            WHERE m_dosen.dosen_id = m_dosen_bidang.dosen_id 
            AND m_dosen_bidang.bidang_id = ? 
        ) as match_bidang", [$bidangId])  // Using positional binding here
            ->selectRaw("EXISTS (
            SELECT 1 
            FROM m_dosen_matkul 
            WHERE m_dosen.dosen_id = m_dosen_matkul.dosen_id 
            AND m_dosen_matkul.mk_id = ? 
        ) as match_matkul", [$matkulId])  // Using positional binding here
            ->orderByRaw('is_in_process ASC, match_bidang DESC, match_matkul DESC')
            ->get();

        return view('pelatihan.createTunjuk', [
            'activeMenu' => 'pelatihan',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
            'dataP' => $dataP,
        ]);
    }

    public function storeTunjuk(Request $request)
    {
        Log::info('Received request data:', $request->all());

        $rules = [
            'level_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            // 'mk_id' => 'required|array|min:1|max:10',
            'mk_id' => 'required|integer',
            // 'bidang_id' => 'required|array|min:1|max:10',
            'vendor_id' => 'required|integer',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'kuota' => 'required|integer',
            'biaya' => 'required|numeric|min:0',
            'dosen_id' => 'required|array|min:1|max:10', // Pastikan dosen_id adalah array
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        try {
            // Simpan data ke tabel t_pelatihan
            $pelatihan = PelatihanModel::create([
                'level_id' => $request->level_id,
                'mk_id' => $request->mk_id,
                'vendor_id' => $request->vendor_id,
                'bidang_id' => $request->bidang_id,
                'nama_pelatihan' => $request->nama_pelatihan,
                'tanggal' => $request->tanggal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'biaya' => $request->biaya,
                'lokasi' => $request->lokasi,
                'periode' => $request->periode,
                'kuota' => $request->kuota,
                'status' => 'Proses',
                'keterangan' => 'Menunggu Validasi',
            ]);

            // Ambil ID dari pelatihan yang baru saja dibuat
            $pelatihanId = $pelatihan->pelatihan_id;

            // Simpan relasi ke tabel t_data_pelatihan
            foreach ($request->dosen_id as $dosenId) {
                DataPelatihanModel::create([
                    'pelatihan_id' => $pelatihanId,
                    'dosen_id' => $dosenId,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Pelatihan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving pelatihan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function show_ajax(string $id)
    {
        // Ambil data Pelatihan berdasarkan id
        $pelatihan = PelatihanModel::find((int) $id);

        // Ambil data DataPelatihan dengan sertifikat di dalamnya
        $dataPelatihan = DataPelatihanModel::where('pelatihan_id', $id)->first();

        // Periksa apakah data ditemukan
        if ($pelatihan !== null && $dataPelatihan !== null) {
            // Tampilkan halaman show_ajax dengan data pelatihan dan sertifikat
            return view('pelatihan.show_ajax', ['dataPelatihan' => $dataPelatihan, 'pelatihan' => $pelatihan]);
        } else {
            // Tampilkan pesan kesalahan jika data tidak ditemukan
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
        $pelatihan = PelatihanModel::select('pelatihan_id')
            ->groupBy('pelatihan_id')
            ->with(['data_pelatihan', 'pelatihan', 'pelatihan.bidang']);

        $hostname = $request->getHost();


        // Menggunakan DataTables untuk memproses data yang telah diambil
        return DataTables::of($pelatihan)
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
                // Ambil pelatihan_id dari $row
                $pelatihanId = $row->pelatihan_id;

                // Query untuk mendapatkan semua status berdasarkan pelatihan_id
                $statuses = DB::table('t_pelatihan')
                    ->where('pelatihan_id', $pelatihanId)
                    ->pluck('status');

                // Gabungkan status menjadi string, atau tampilkan '-' jika tidak ada
                return $statuses->implode(', ') ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                // Tombol aksi
                $btn  = '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan_id) . '/show_ajax' . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->pelatihan->pelatihan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->addColumn('surat', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/pelatihan/' . $row->data_pelatihan_id) . '/export_ajax' . '\')" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Download</button> ';
            })

            ->rawColumns(['aksi']) // Pastikan HTML tombol dirender dengan benar
            ->make(true);
        // ->rawColumns(['surat']) // Pastikan HTML tombol dirender dengan benar
        // ->make(true);
    }


    public function create_ajax()
    {

        $dosens = DB::table('m_dosen')
            ->join('m_user', 'm_dosen.user_id', '=', 'm_user.user_id')
            ->select('m_dosen.dosen_id', 'm_user.nama')
            ->get();

        $breadcrumb = (object) [
            'title' => 'Pelatihan Dosen',
            'subtitle' => 'Tambah Pelatihan'
        ];

        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor
        $levels = LevelPelatihanModel::all();

        return view('pelatihan.create_ajax', [
            'activeMenu' => 'pelatihan',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'dosens' => $dosens,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
        ]);
        // return view('pelatihan.createTunjuk');

    }
    public function store_ajax(Request $request)
    {
        Log::info('Received request data:', $request->all());

        // Cek apakah request berupa AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $rules = [
                'dosen_id' => 'required|exists:m_dosen,dosen_id',
                'level_id' => 'required|integer',
                'bidang_id' => 'required|integer',
                'mk_id' => 'required|integer',
                'vendor_id' => 'required|integer',
                'nama_pelatihan' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
                'lokasi' => 'required|string|max:255',
                'periode' => 'required|string|max:50',
                'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                // Tentukan direktori tujuan
                $destinationPath = public_path('file_bukti_pel');

                // Buat folder jika belum ada
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }

                // Simpan file jika diunggah
                $filePath = null;
                if ($request->hasFile('file')) {
                    $file = $request->file('file');

                    if ($file->isValid()) {
                        Log::info('File details:', [
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]);

                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move($destinationPath, $fileName);
                        $filePath = $fileName;
                    } else {
                        Log::error('File is not valid.');
                        return response()->json([
                            'status' => false,
                            'message' => 'File tidak valid.',
                        ]);
                    }
                } else {
                    Log::error('File tidak ditemukan dalam request.');
                    return response()->json([
                        'status' => false,
                        'message' => 'File tidak ditemukan dalam request.',
                    ]);
                }

                $pelatihanData = $request->except('file');
                $pelatihanData['keterangan'] = 'Menunggu Validasi';
                $pelatihanData['status'] = 'Proses';
                $pelatihanData['kuota'] = '1';

                // Buat record sertifikasi
                $pelatihan = PelatihanModel::create($pelatihanData);

                Log::info('Pelatihan created:', $pelatihan->toArray());

                // Buat record DataPelatihan dan simpan hanya nama file di kolom sertifikat
                DataPelatihanModel::create([
                    'pelatihan_id' => $pelatihan->pelatihan_id,
                    'dosen_id' => $request->input('dosen_id', null),
                    'surat_tugas_id' => $request->input('surat_tugas_id', null),
                    'sertifikat' => $filePath,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Pelatihan berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                Log::error('Error saving pelatihan:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all(),
                ]);

                $errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                    $errorMessage .= ': Kesalahan database';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Jika bukan AJAX, redirect ke halaman utama
        return redirect('/pelatihan');
    }


    public function create_ajax2()
    {

        $dosens = DB::table('m_dosen')
            ->join('m_user', 'm_dosen.user_id', '=', 'm_user.user_id')
            ->select('m_dosen.dosen_id', 'm_user.nama')
            ->get();

        $breadcrumb = (object) [
            'title' => 'Pelatihan Dosen',
            'subtitle' => 'Tambah Pelatihan'
        ];

        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor
        $levels = LevelPelatihanModel::all();

        return view('pelatihan.create_ajax2', [
            'activeMenu' => 'pelatihan',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'dosens' => $dosens,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
        ]);
        // return view('pelatihan.createTunjuk');

    }

    public function store_ajax2(Request $request)
    {
        // Ambil user yang login
        $dosen = Auth::user()->dosen->dosen_id;

        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan untuk pengguna yang login.',
            ]);
        }

        // Tambahkan dosen_id ke dalam request
        $request->merge(['dosen_id' => $dosen]);

        Log::info('Request data after adding dosen_id:', $request->all());

        // Validasi input
        $rules = [
            'level_id' => 'required|integer',
            'bidang_id' => 'required|integer',
            'mk_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal',
            'lokasi' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        try {
            // Tentukan direktori penyimpanan file
            $destinationPath = public_path('file_bukti_pel');

            // Buat folder jika belum ada
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Simpan file
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if ($file->isValid()) {
                    Log::info('File details:', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move($destinationPath, $fileName);
                    $filePath = $fileName;
                } else {
                    Log::error('File is not valid.');
                    return response()->json([
                        'status' => false,
                        'message' => 'File tidak valid.',
                    ]);
                }
            } else {
                Log::error('File tidak ditemukan dalam request.');
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan dalam request.',
                ]);
            }

            // Siapkan data untuk tabel pelatihan
            $pelatihanData = $request->except('file');
            $pelatihanData['dosen_id'] = $dosen; // Gunakan dosen_id dari relasi user_id
            $pelatihanData['keterangan'] = 'Mandiri';
            $pelatihanData['status'] = 'Selesai';
            $pelatihanData['kuota'] = '1';

            // Buat record pelatihan
            $pelatihan = PelatihanModel::create($pelatihanData);

            Log::info('Pelatihan created:', $pelatihan->toArray());

            // Buat record DataPelatihan dengan file sertifikat
            DataPelatihanModel::create([
                'pelatihan_id' => $pelatihan->pelatihan_id,
                'dosen_id' => $dosen,
                'surat_tugas_id' => $request->input('surat_tugas_id', null),
                'sertifikat' => $filePath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pelatihan berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving pelatihan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            $errorMessage = 'Terjadi kesalahan saat menyimpan data.';
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                $errorMessage .= ' Kesalahan database.';
            }

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
                'error' => $e->getMessage(),
            ]);
        }
        return redirect('/pelatihan/dosen');
    }




    public function edit_ajax($id)
    {
        // Fetch data related to the specific jamKompen
        $pelatihan = PelatihanModel::with('data_pelatihan', 'level', 'bidang',  'matkul',  'vendor')->find($id);
        if (!$pelatihan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        // Fetch dropdown data
        $level = LevelPelatihanModel::select('level_id', 'level_nama')->get();
        $bidang = BidangModel::select('bidang_id', 'bidang_nama')->get();
        $vendor = VendorModel::select('vendor_id', 'vendor_nama')->get();
        $matkul = MatkulModel::select('mk_id', 'mk_nama')->get();

        // Pass data to view
        return view('pelatihan.edit_ajax', compact('pelatihan', 'level', 'bidang',  'matkul',  'vendor'));
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
                'lokasi' => 'required|string|max:255',
                'periode' => 'required|string|max:50',
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

    public function confirm_ajax(string $pelatihan_id)
    {
        // Cari data pelatihan berdasarkan ID
        $pelatihan = PelatihanModel::find($pelatihan_id);

        // Jika pelatihan tidak ditemukan
        if (!$pelatihan) {
            return response()->json([
                'status' => false,
                'message' => 'Pelatihan tidak ditemukan'
            ]);
        }

        $dataPelatihan = DataPelatihanModel::where('pelatihan_id', $pelatihan_id)->get();

        return view('pelatihan.confirm_ajax', [
            'pelatihan' => $pelatihan,
            'dataPelatihan' => $dataPelatihan
        ]);
    }

    public function delete_ajax(Request $request, string $pelatihan_id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Cari data penjualan
            $pelatihan = PelatihanModel::findOrFail($pelatihan_id);

            if ($pelatihan) {
                try {
                    // Hapus semua detail penjualan yang terkait
                    DataPelatihanModel::where('pelatihan_id', $pelatihan_id)->delete();

                    // Hapus data penjualan
                    $pelatihan->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data Pelatihan berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terkait dengan data lain'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Pelatihan tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
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

    // function upload surat tugas
    public function uploadBukti(Request $request)
    {
        try {
            // Validasi file yang diupload dengan pesan error khusus
            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) {
                        // Cek ekstensi file
                        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        $extension = $value->getClientOriginalExtension();
                        if (!in_array(strtolower($extension), $allowedExtensions)) {
                            $fail('Tipe file tidak diizinkan. Hanya file PDF, DOC, DOCX, XLS, dan XLSX yang diperbolehkan.');
                        }

                        // Cek ukuran file (2MB = 2048 KB)
                        $maxFileSize = 2048; // dalam KB
                        $fileSize = $value->getSize() / 1024; // konversi ke KB
                        if ($fileSize > $maxFileSize) {
                            $fail('Ukuran file terlalu besar. Maksimal 2 MB.');
                        }
                    },
                ],
                'id_kegiatan' => 'required|exists:t_kegiatan,id_kegiatan',
            ]);

            // Jika validasi gagal, lempar exception
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Periksa apakah file ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Buat nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();

                // Simpan file di direktori 'public/dokumen'
                $path = $file->storeAs('dokumen', $filename, 'public');

                // Buat record dokumen di database
                $dokumen = DataPelatihanModel::create([
                    'id_kegiatan' => $request->id_kegiatan,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'jenis_dokumen' => 'surat tugas',
                    'file_path' => $path,
                    'progress' => 0, // Progress awal
                ]);

                // Kembalikan respon sukses dengan SweetAlert
                return back()->with('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'File berhasil diupload.',
                    'icon' => 'success'
                ]);
            }

            // Jika tidak ada file
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Tidak ada file yang diupload.',
                'icon' => 'error'
            ]);
        } catch (ValidationException $e) {
            // Tangani kesalahan validasi dengan SweetAlert
            $errors = $e->validator->errors()->all();
            return back()->with('swal', [
                'title' => 'Validasi Gagal!',
                'text' => implode('\n', $errors),
                'icon' => 'error'
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan umum dengan SweetAlert
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal mengupload file: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
    public function downloadSertifikat($pelatihan_id)
    {
        try {
            // Cari data pelatihan berdasarkan ID
            $dataPelatihan = DataPelatihanModel::where('pelatihan_id', $pelatihan_id)
                ->firstOrFail();

            // Dapatkan nama file yang tersimpan di kolom sertifikat
            $fileName = $dataPelatihan->sertifikat;

            if (empty($fileName)) {
                return back()->with('error', 'Tidak ada file sertifikat yang tersimpan.');
            }

            // Buat path lengkap menuju file di folder 'public/file_bukti_pel'
            $fullFilePath = public_path('file_bukti_pel/' . $fileName);

            // Periksa apakah file ada
            if (!file_exists($fullFilePath)) {
                Log::error('File not found:', [
                    'pelatihan_id' => $pelatihan_id,
                    'file_path' => $fullFilePath
                ]);
                return back()->with('error', 'File tidak ditemukan di server.');
            }

            // Log activity
            Log::info('Downloading sertifikat:', [
                'pelatihan_id' => $pelatihan_id,
                'file_name' => $fileName
            ]);

            // Return file download
            return response()->download($fullFilePath);
        } catch (\Exception $e) {
            Log::error('Error downloading sertifikat:', [
                'pelatihan_id' => $pelatihan_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal mendownload file: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $pelatihan = PelatihanModel::with('bidang')->findOrFail($id);
        $bidang = $pelatihan->bidang;

        $breadcrumb = (object) [
            'title' => $pelatihan->nama_pelatihan, // Sesuaikan dengan kolom yang benar
            'subtitle' => $bidang ? $bidang->bidang_nama : 'N/A'
        ];
        return view('pelatihan.detail_pelatihan', [
            'pelatihan' => $pelatihan,
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function export_ajax(Request $request, $pelatihan_id)
    {
        $pelatihan = DB::table('t_pelatihan')->where('pelatihan_id', $pelatihan_id)->first();

        if (!$pelatihan || $pelatihan->keterangan !== 'Validasi Disetujui') {
            return response()->json([
                'status' => false,
                'message' => 'Dokumen belum bisa diunduh. Tunggu validasi.',
            ]);
        } elseif ($pelatihan || $pelatihan->keterangan == 'Validasi Ditolak') {
            return response()->json([
                'status' => false,
                'message' => 'Dokumen tidak bisa diunduh. Validasi ditolak.',
            ]);
        }

        $dosenList = DB::table('t_data_pelatihan')
            ->join('m_dosen', 'm_dosen.dosen_id', '=', 't_data_pelatihan.dosen_id')
            ->join('m_user', 'm_user.user_id', '=', 'm_dosen.user_id')
            ->where('t_data_pelatihan.pelatihan_id', $pelatihan_id)
            ->select('m_user.nama as dosen_nama')
            ->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText("Surat Tugas", ['bold' => true, 'size' => 16]);
        $section->addText("Kepada");
        $section->addText("Yth. Pembantu Direktur I Politeknik Negeri Malang");
        $section->addTextBreak();

        $section->addText("Dengan Hormat,");
        $section->addTextBreak();

        $section->addText("Sehubungan dengan pelaksanaan kegiatan \"" . $pelatihan->nama_pelatihan . "\" D4 Sistem Informasi Bisnis yang diselenggarakan oleh Jurusan Teknologi Informasi pada bulan " . date('F Y', strtotime($pelatihan->tanggal)) . ", mohon untuk dapat dibuatkan surat tugas kepada nama-nama di bawah ini:", ['size' => 12]);
        $section->addTextBreak();

        // Tabel Anggota
        $styleTable = [
            'borderSize' => 4,
            'borderColor' => '000000',
            'cellMargin' => 80
        ];
        $phpWord->addTableStyle('Daftar Dosen', $styleTable);
        $table = $section->addTable('Daftar Dosen');

        $table->addRow();
        $table->addCell(500)->addText('No', ['bold' => true, 'size' => 10]);
        $cell = $table->addCell(5000);
        $textRun = $cell->addTextRun();
        $textRun->addText('Nama', ['bold' => true]);
        $textRun->addTextBreak();
        $textRun->addText('NIP');

        foreach ($dosenList as $index => $dosen) {
            $table->addRow();
            $table->addCell(500)->addText($index + 1);
            $table->addCell(5000)->addText($dosen->dosen_nama);
        }
        $section->addTextBreak();

        $section->addText("Demikian surat permohonan ini dibuat. Atas perhatian dan kerjasamanya, kami sampaikan terima kasih.", ['size' => 12]);

        $signatureTable = $section->addTable();
        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
        $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("Ketua Jurusan Teknologi Informasi,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addTextBreak(2);
        $signatureCell->addText("Dr. Eng Rosa Andrie Asmara, S.T., M.T.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        $fileName = 'surat_tugas_' . time() . '.docx';
        $filePath = storage_path("app/public/$fileName");
        $phpWord->save($filePath, 'Word2007');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function uploadSurat(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nomor_surat' => 'required|unique:m_surat_tugas,nomor_surat',
                'file' => [
                    'required',
                    'file',
                    // Validasi ekstensi dan ukuran file
                ],
                'pelatihan_id' => 'required|exists:t_kegiatan,pelatihan_id',
                'dosen_id' => 'required|exists:m_dosen,dosen_id',
            ]);

            // Jika validasi gagal, lempar exception
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Mulai transaksi database
            DB::beginTransaction();

            // Proses upload file surat tugas
            $fileSuratTugas = $request->file('file');
            $filenameSuratTugas = time() . '_surat_' . $fileSuratTugas->getClientOriginalName();
            $pathSuratTugas = $fileSuratTugas->storeAs('dokumen/surat_tugas', $filenameSuratTugas, 'public');

            // Simpan data surat tugas
            $suratTugas = SuratTugasModel::create([
                'nomor_surat' => $request->nomor_surat,
                'nama_surat' => $filenameSuratTugas, // Simpan nama file di kolom nama_surat
                'status' => 'Diterima', // Set status surat tugas menjadi "Diterima"
            ]);

            // Simpan data pelatihan
            $dataPelatihan = DataPelatihanModel::create([
                'pelatihan_id' => $request->pelatihan_id,
                'dosen_id' => $request->dosen_id,
                'surat_tugas_id' => $suratTugas->surat_tugas_id,
            ]);

            // Commit transaksi
            DB::commit();

            // Kembalikan respon sukses dengan SweetAlert
            return back()->with('swal', [
                'title' => 'Berhasil!',
                'text' => 'Surat tugas dan data pelatihan berhasil disimpan.',
                'icon' => 'success'
            ]);
        } catch (ValidationException $e) {
            // Rollback transaksi jika validasi gagal
            DB::rollBack();

            // Tangani kesalahan validasi dengan SweetAlert
            $errors = $e->validator->errors()->all();
            return back()->with('swal', [
                'title' => 'Validasi Gagal!',
                'text' => implode('\n', $errors),
                'icon' => 'error'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Tangani kesalahan umum dengan SweetAlert
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal menyimpan surat tugas: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Tambahan method untuk download surat tugas
    public function downloadSuratTugas($suratTugasId)
    {
        try {
            $suratTugas = SuratTugasModel::findOrFail($suratTugasId);

            $pathToFile = storage_path('app/public/dokumen/surat_tugas/' . $suratTugas->nama_surat);

            // Cek apakah file ada
            if (!file_exists($pathToFile)) {
                return back()->with('swal', [
                    'title' => 'Gagal!',
                    'text' => 'File surat tugas tidak ditemukan.',
                    'icon' => 'error'
                ]);
            }

            // Download file
            return response()->download($pathToFile, $suratTugas->nama_surat);
        } catch (\Exception $e) {
            return back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Error mengunduh surat tugas: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function upload_(Request $request)
    {
        Log::info('Received upload request:', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
            'pelatihan_id' => 'required|integer',
            'dosen_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            // Direktori tujuan penyimpanan file
            $destinationPath = public_path('dokumen/surat_tugas');

            // Buat folder jika belum ada
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Simpan file
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if ($file->isValid()) {
                    // Generate nama file unik
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move($destinationPath, $fileName);

                    Log::info('File uploaded successfully:', ['file_name' => $fileName]);

                    // Format nomor_surat: pelatihan_(pelatihan_id)
                    $nomorSurat = 'pelatihan_' . $request->pelatihan_id;

                    // Simpan ke tabel m_surat_tugas
                    $suratTugas = SuratTugasModel::create([
                        'nama_surat' => $fileName,
                        'nomor_surat' => $nomorSurat, // Nomor surat otomatis
                        'status' => 'Proses',
                    ]);

                    Log::info('Surat Tugas created:', $suratTugas->toArray());

                    // Update tabel data_pelatihan
                    DataPelatihanModel::where('pelatihan_id', $request->pelatihan_id)
                        ->update(['surat_tugas_id' => $suratTugas->surat_tugas_id]);

                    Log::info('Data Pelatihan updated with surat_tugas_id.');

                    PelatihanModel::where('pelatihan_id', $request->pelatihan_id)
                        ->update(['keterangan' => 'Penunjukkan']);
                    return response()->json([
                        'status' => true,
                        'message' => 'Surat Tugas berhasil diupload dan disimpan.',
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'File tidak valid atau gagal diunggah.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error during upload:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
