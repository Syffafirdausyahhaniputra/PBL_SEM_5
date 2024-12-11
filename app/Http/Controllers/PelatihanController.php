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
use App\Models\JenisModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
// use App\Http\Controllers\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

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

        // Ambil data dosen beserta nama user
        $dataP = DosenModel::with('user')->get();

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
                'kuota' => 'required|integer',
                'biaya' => 'required|numeric|min:0',
                // 'dosen_ids' => 'required|array|min:1|max:10',
                // 'dosen_ids.*' => 'exists:m_dosen,dosen_id|distinct',


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

                // Create Pelatihan 
                $pelatihan = PelatihanModel::create($request->except('file')); // Save all fields except 'file'

                // Log the created model
                Log::info('Pelatihan created:', $pelatihan->toArray());

                // Create DataPelatihan record
                DataPelatihanModel::create([
                    'pelatihan_id' => $pelatihan->pelatihan_id, // ID pelatihan yang baru saja disimpan
                    'dosen_id' => $request->input('dosen_id', null), // Sesuaikan input dosen_id
                    'keterangan' => 'Menunggu validasi', // Atur keterangan default
                    'status' => 'Proses', // Atur status default
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Pelatihan berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                Log::error('Error saving pelatihan:', [
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
        return redirect('/pelatihan');
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
                // Tampilkan tanggal pelatihan dari relasi pelatihan
                return $row->data_pelatihan->pluck('status')?? '-';
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
                    'msgField' => $validator->errors(), // Pesan error validasi
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
                    $fileName = time() . '_' . $file->getClientOriginalName(); // Nama file unik
                    $file->move($destinationPath, $fileName); // Simpan ke folder public/file_bukti_pel
    
                    // Simpan nama file tanpa folder (hanya nama file)
                    $filePath = $fileName;
                }
    
                // Buat record Pelatihan
                $pelatihan = PelatihanModel::create($request->except('file')); // Simpan semua data kecuali file
    
                // Log model yang dibuat
                Log::info('Pelatihan created:', $pelatihan->toArray());
    
                // Buat record DataPelatihan dan simpan hanya nama file di kolom sertifikat
                DataPelatihanModel::create([
                    'pelatihan_id' => $pelatihan->pelatihan_id, // ID pelatihan yang baru disimpan
                    'dosen_id' => $request->input('dosen_id', null),
                    'surat_tugas_id' => $request->input('surat_tugas_id', null),
                    'keterangan' => 'Mandiri',
                    'status' => 'Proses',
                    'sertifikat' => $filePath, // Menyimpan hanya nama file
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
        
}