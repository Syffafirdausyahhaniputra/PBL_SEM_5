<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\DataSertifikasiModel;
use App\Models\BidangModel;
use App\Models\DosenModel;
use App\Models\JenisModel;
use App\Models\LevelPelatihanModel;
use App\Models\MatkulModel;
use App\Models\VendorModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;


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
    // Ambil data Pelatihan berdasarkan id
    $sertifikasi = SertifikasiModel::find((int) $id);

    // Ambil data DataPelatihan dengan sertifikat di dalamnya
    $dataSertifikasi = DataSertifikasiModel::where('sertif_id', $id)->first();

    // Periksa apakah data ditemukan
    if ($sertifikasi !== null && $dataSertifikasi !== null) {
        // Tampilkan halaman show_ajax dengan data pelatihan dan sertifikat
        return view('sertifikasi.show_ajax', ['dataSertifikasi' => $dataSertifikasi, 'sertifikasi' => $sertifikasi]);
    } else {
        // Tampilkan pesan kesalahan jika data tidak ditemukan
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
        $levels = LevelPelatihanModel::all();

        // Ambil data dosen beserta nama user
        $dataP = DosenModel::with('user')->get();

        return view('sertifikasi.createTunjuk', [
            'activeMenu' => 'sertifikasi',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'jenis' => $jenis,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'levels' => $levels,
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
        $sertifikasi = SertifikasiModel::select('sertif_id')
            ->groupBy('sertif_id')
            ->with('data_sertifikasi', 'sertifikasi', 'sertifikasi.bidang');

            $hostname = $request->getHost();

        return DataTables::of($sertifikasi)
            ->addIndexColumn()
            ->addColumn('nama_sertif', function ($row) {
                return $row->sertifikasi->nama_sertif ?? '-';
            })
            ->addColumn('nama_bidang', function ($row) {
                return $row->sertifikasi->bidang->bidang_nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->sertifikasi->tanggal ?? '-';
            })
            ->addColumn('status', function ($row) {
                return $row->sertifikasi->status ?? '-';
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

        $dosens = DB::table('m_dosen')
        ->join('m_user', 'm_dosen.user_id', '=', 'm_user.user_id')
        ->select('m_dosen.dosen_id', 'm_user.nama')
        ->get();

        $breadcrumb = (object) [
            'title' => 'Sertifikasi Dosen',
            'subtitle' => 'Tambah Sertifikasi'
        ];

        $bidangs = BidangModel::all();
        $matkuls = MatkulModel::all(); // Ambil data mata kuliah
        $vendors = VendorModel::all(); // Ambil data vendor
        $jenis = JenisModel::all();

        return view('sertifikasi.create_ajax', [
            'activeMenu' => 'sertifikasi',
            'breadcrumb' => $breadcrumb,
            'bidangs' => $bidangs,
            'dosens' => $dosens,
            'matkuls' => $matkuls,
            'vendors' => $vendors,
            'jenis' => $jenis,
        ]);

    }


   public function store_ajax(Request $request)
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
            'nama_sertif' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'required|date|after_or_equal:tanggal',
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
            $destinationPath = public_path('file_bukti_ser');

            // Buat folder jika belum ada
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Simpan file jika diunggah
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);
                $filePath = $fileName;
            }

            // Tambahkan nilai default untuk 'keterangan' dan 'status'
            $sertifikasiData = $request->except('file');
            $sertifikasiData['keterangan'] = 'Mandiri';
            $sertifikasiData['status'] = 'Selesai';

            // Buat record sertifikasi
            $sertifikasi = SertifikasiModel::create($sertifikasiData);

            // Log model yang dibuat
            Log::info('Sertifikasi created:', $sertifikasi->toArray());

            // Buat record DataSertifikasi dan simpan hanya nama file di kolom sertifikat
            DataSertifikasiModel::create([
                'sertif_id' => $sertifikasi->sertif_id,
                'dosen_id' => $request->input('dosen_id', null),
                'surat_tugas_id' => $request->input('surat_tugas_id', null),
                'sertifikat' => $filePath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Sertifikasi berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving sertifikasi:', [
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
    return redirect('/sertifikasi');
}

    public function edit_ajax($id)
    {
        // Fetch data related to the specific jamKompen
        $sertifikasi = SertifikasiModel::with('data_sertifikasi', 'jenis', 'bidang',  'matkul',  'vendor')->find($id);
        if (!$sertifikasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        // Fetch dropdown data
        $jenis = JenisModel::select('jenis_id', 'jenis_nama')->get();
        $bidang = BidangModel::select('bidang_id', 'bidang_nama')->get();
        $vendor = VendorModel::select('vendor_id', 'vendor_nama')->get();
        $matkul = MatkulModel::select('mk_id', 'mk_nama')->get();

        // Pass data to view
        return view('sertifikasi.edit_ajax', compact('sertifikasi', 'jenis', 'bidang',  'matkul',  'vendor'));
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

    public function confirm_ajax(string $sertif_id)
    {
        // Cari data pelatihan berdasarkan ID
        $sertifikasi = SertifikasiModel::find($sertif_id);

        // Jika pelatihan tidak ditemukan
        if (!$sertifikasi) {
            return response()->json([
                'status' => false,
                'message' => 'Pelatihan tidak ditemukan'
            ]);
        }

        $dataSertifikasi = DataSertifikasiModel::where('sertif_id', $sertif_id)->get();

        return view('sertifikasi.confirm_ajax', [
            'sertifikasi' => $sertifikasi,
            'dataSertifikasi' => $dataSertifikasi
        ]);

    }
    
    public function delete_ajax(Request $request, string $sertif_id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Cari data penjualan
            $sertifikasi = SertifikasiModel::findOrFail($sertif_id);

            if ($sertifikasi) {
                try {
                    // Hapus semua detail penjualan yang terkait
                    DataSertifikasiModel::where('sertif_id', $sertif_id)->delete();

                    // Hapus data penjualan
                    $sertifikasi->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data Sertifikasi berhasil dihapus'
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
                    'message' => 'Data Sertifikasi tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
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
                $dokumen = DataSertifikasiModel::create([
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

    public function downloadSertifikat($sertif_id)
        {
            try {
                // Cari data pelatihan berdasarkan ID
                $dataSertifikasi = DataSertifikasiModel::where('sertif_id', $sertif_id)
                    ->firstOrFail();
        
                // Dapatkan nama file yang tersimpan di kolom sertifikat
                $fileName = $dataSertifikasi->sertifikat;
        
                if (empty($fileName)) {
                    return back()->with('error', 'Tidak ada file sertifikat yang tersimpan.');
                }
        
                // Buat path lengkap menuju file di folder 'public/file_bukti_pel'
                $fullFilePath = public_path('file_bukti_ser/' . $fileName);
        
                // Periksa apakah file ada
                if (!file_exists($fullFilePath)) {
                    Log::error('File not found:', [
                        'sertif_id' => $sertif_id,
                        'file_path' => $fullFilePath
                    ]);
                    return back()->with('error', 'File tidak ditemukan di server.');
                }
        
                // Log activity
                Log::info('Downloading sertifikat:', [
                    'sertif_id' => $sertif_id,
                    'file_name' => $fileName
                ]);
        
                // Return file download
                return response()->download($fullFilePath);
        
            } catch (\Exception $e) {
                Log::error('Error downloading sertifikat:', [
                    'sertif_id' => $sertif_id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->with('error', 'Gagal mendownload file: ' . $e->getMessage());
            }
        }

    public function detail($id)
    {
        $sertifikasi = SertifikasiModel::with('bidang')->findOrFail($id);  
        $bidang = $sertifikasi->bidang; 

        $breadcrumb = (object) [
            'title' => $sertifikasi->nama_sertif, // Sesuaikan dengan kolom yang benar
            'subtitle' => $bidang ? $bidang->bidang_nama : 'N/A'
        ];
        return view('sertifikasi.detail_sertif', [
            'sertifikasi' => $sertifikasi,
            'breadcrumb' => $breadcrumb
        ]);
    }
    public function export_ajax(Request $request, $sertif_id)
    {
        $sertifikasi = DB::table('t_sertifikasi')->where('sertif_id', $sertif_id)->first();
    
        if (!$sertifikasi || $sertifikasi->keterangan !== 'sudah divalidasi') {
            return response()->json([
                'status' => false,
                'message' => 'Dokumen belum bisa diunduh. Tunggu validasi.',
            ]);
        }
    
        $dosenList = DB::table('t_data_sertifikasi')
        ->join('m_dosen', 'm_dosen.dosen_id', '=', 't_data_sertifikasi.dosen_id')
        ->join('m_user', 'm_user.user_id', '=', 'm_dosen.user_id')
        ->where('t_data_sertifikasi.sertif_id', $sertif_id)
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

        $section->addText("Sehubungan dengan pelaksanaan kegiatan \"" . $sertifikasi->nama_sertif . "\" D4 Sistem Informasi Bisnis yang diselenggarakan oleh Jurusan Teknologi Informasi pada bulan " . date('F Y', strtotime($sertifikasi->tanggal)) . ", mohon untuk dapat dibuatkan surat tugas kepada nama-nama di bawah ini:", ['size' => 12]);
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

    // Menambahkan tanda tangan dengan perataan kanan
    $section->addTextBreak(2);
    $section->addText("Malang, " . date('d F Y'), null, ['alignment' => Jc::END]);
    $section->addText("Ketua Jurusan Teknologi Informasi,", null, ['alignment' => Jc::END]);
    $section->addTextBreak(3);
    $section->addText("Dr. Eng Rosa Andrie Asmara, S.T., M.T.", ['bold' => true], ['alignment' => Jc::END]);
    $section->addText("NIP. 196602141990032002", null, ['alignment' => Jc::END]);

        $fileName = 'surat_tugas_' . $sertifikasi->nama_sertif . '.docx';
        $filePath = storage_path("app/public/$fileName");
        $phpWord->save($filePath, 'Word2007');
    
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
