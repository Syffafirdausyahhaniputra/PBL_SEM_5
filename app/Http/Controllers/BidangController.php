<?php

namespace App\Http\Controllers;

use App\Models\BidangModel;
use App\Models\DosenBidangModel;
use App\Models\DosenModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataPelatihanModel;
use App\Models\DataSertifikasiModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Support\Facades\Log;

class BidangController extends Controller
{
    // Menampilkan halaman awal bidang
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Bidang',
            'subtitle'  => 'Daftar bidang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'bidang'; // set menu yang sedang aktif

        return view('bidang.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }
    public function index1()
    {
        return view('bidang.index1');
    }
    public function index2()
    {
        $bidangs = BidangModel::all();
        $breadcrumb = (object) [
            'title' => 'Daftar Bidang',
            'subtitle'  => 'Daftar bidang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'bidang'; // set menu yang sedang aktif

        return view('bidang.indexDetailBidang', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'bidangs' => $bidangs
        ]);
    }

    // Ambil data bidang dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $bidangs = BidangModel::select('bidang_id', 'bidang_nama');

        return DataTables::of($bidangs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($bidang) {
                $btn  = '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_dosen()
    {
        $bidangs = DosenBidangModel::all();
        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'subtitle'  => 'Halo..'
        ];

        $activeMenu = 'bidang'; // set menu yang sedang aktif

        return view('bidang.show_dosen', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'bidangs' => $bidangs
        ]);
    }

    public function create_ajax()
    {
        return view('bidang.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'bidang_kode' => 'required|string|min:3|unique:m_bidang,bidang_kode',
                'bidang_nama' => 'required|string|max:100',
            ];

            // Validasi data inputan
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Simpan data ke database
            BidangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Bidang berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $bidang = BidangModel::find($id);

        // Jika bidang tidak ditemukan
        if (!$bidang) {
            return response()->json([
                'status' => false,
                'message' => 'Bidang tidak ditemukan'
            ]);
        }

        return view('bidang.edit_ajax', ['bidang' => $bidang]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'bidang_kode' => 'required|string|max:5|unique:m_bidang,bidang_kode,' . $id . ',bidang_id',
                'bidang_nama' => 'required|string|max:100',
            ];

            // Validasi data input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $bidang = BidangModel::find($id);
            if ($bidang) {
                $bidang->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Jabatan berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Jabatan tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $bidang = BidangModel::find($id);
        return view('bidang.confirm_ajax', ['bidang' => $bidang]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $bidang = BidangModel::find($id);
            if ($bidang) {
                $bidang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } else {
            return redirect('/');
        }
    }
    public function show_ajax(string $id)
    {
        // Cari bidang berdasarkan id
        $bidang = BidangModel::find($id);

        // Periksa apakah bidang ditemukan
        if ($bidang) {
            // Tampilkan halaman show_ajax dengan data bidang
            return view('bidang.show_ajax', ['bidang' => $bidang]);
        } else {
            // Tampilkan pesan kesalahan jika bidang tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('bidang.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_bidang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_bidang');  // ambil file dari request 

            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 

            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 

            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati 
                        $insert[] = [
                            'bidang_kode' => $value['A'],
                            'bidang_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    BidangModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    
    public function showDosenByBidang($id)
{
    // Ambil data bidang berdasarkan ID
    $bidang = BidangModel::findOrFail($id);

    // Ambil daftar dosen berdasarkan bidang dengan relasi
    $dosen = DosenBidangModel::where('bidang_id', $id)
                ->with('dosen2.user')
                ->get();

    $breadcrumb = (object) [
        'title' => $bidang->bidang_nama,
        'subtitle'  => 'List dosen bidang '. $bidang->bidang_nama
    ];

    // Tampilkan view dengan data dosen
    return view('bidang.show_dosen', [
        'bidang' => $bidang,
        'dosen' => $dosen,
        'breadcrumb' => $breadcrumb
    ]);
}

public function info_dosen($id, $id_dosen)
{
    $bidang = BidangModel::findOrFail($id);

    // Ambil data dosen spesifik berdasarkan bidang dan id_dosen
    $dosen = DosenBidangModel::where('bidang_id', $id)
                ->whereHas('dosen2', function ($query) use ($id_dosen) {
                    $query->where('dosen_id', $id_dosen);
                })
                ->with('dosen2.user')
                ->firstOrFail();

    // Ambil data sertifikasi yang terkait dengan dosen dan bidang melalui relasi dosenBidang
    $sertifikasi = DataSertifikasiModel::with('sertif','sertif.bidang.dosenBidang')
                    ->where('dosen_id', $id_dosen)
                    ->whereHas('sertif.bidang.dosenBidang', function ($query) use ($id, $id_dosen) {
                        $query->where('bidang_id', $id)
                              ->where('dosen_id', $id_dosen);
                    })
                    ->get();
    Log::info($sertifikasi);

    // Ambil data pelatihan yang terkait dengan dosen dan bidang melalui relasi dosenBidang
    $pelatihan = DataPelatihanModel::with('pelatihan','pelatihan.bidang.dosenBidang')
                    ->where('dosen_id', $id_dosen)
                    ->whereHas('pelatihan.bidang.dosenBidang', function ($query) use ($id, $id_dosen) {
                        $query->where('bidang_id', $id)
                              ->where('dosen_id', $id_dosen);
                    })
                    ->get();

    // Hitung jumlah sertifikasi dan pelatihan
    $jumlahSertifikasiPelatihan = $sertifikasi->count() + $pelatihan->count();

    $breadcrumb = (object) [
        'title' => $bidang->bidang_nama,
        'subtitle' => 'List Sertifikasi dan Pelatihan ' . $bidang->bidang_nama
    ];

    // Tampilkan view dengan data dosen
    return view('bidang.informasi_dosen', [
        'bidang' => $bidang,
        'dosen' => $dosen,
        'sertifikasi' => $sertifikasi,
        'pelatihan' => $pelatihan,
        'jumlahSertifikasiPelatihan' => $jumlahSertifikasiPelatihan,
        'breadcrumb' => $breadcrumb
    ]);
}

public function detail_sertif($id, $id_dosen)
{
    $dosenBidang = DosenBidangModel::where('bidang_id', $id)
                ->whereHas('dosen2', function ($query) use ($id_dosen) {
                    $query->where('dosen_id', $id_dosen);
                })
                ->with('dosen2.user')
                ->firstOrFail();

    // Ambil data sertifikasi berdasarkan bidang_id
    $sertifikasi = SertifikasiModel::with('bidang')
                    ->where('bidang_id', $id)
                    ->firstOrFail(); // Ambil data pertama sesuai kondisi

    // Ambil data bidang dari relasi
    $bidang = $sertifikasi->bidang;

    // Menyiapkan breadcrumb
    $breadcrumb = (object) [
        'title' => $sertifikasi->nama_sertif, // Sesuaikan dengan kolom yang benar
        'subtitle' => $bidang ? $bidang->bidang_nama : 'N/A'
    ];

    // Tampilkan view dengan data sertifikasi dan breadcrumb
    return view('bidang.detailSertif', [
        'sertifikasi' => $sertifikasi,
        'breadcrumb' => $breadcrumb,
        'bidang' => $bidang,
        'dosenBidang' => $dosenBidang
    ]);
}

public function detail_pelatihan($id, $id_dosen)
{
    $dosenBidang = DosenBidangModel::where('bidang_id', $id)
                ->whereHas('dosen2', function ($query) use ($id_dosen) {
                    $query->where('dosen_id', $id_dosen);
                })
                ->with('dosen2.user')
                ->firstOrFail();

    // Ambil data sertifikasi berdasarkan bidang_id
    $pelatihan = PelatihanModel::with('bidang')
                    ->where('bidang_id', $id)
                    ->firstOrFail(); // Ambil data pertama sesuai kondisi

    // Ambil data bidang dari relasi
    $bidang = $pelatihan->bidang;

    // Menyiapkan breadcrumb
    $breadcrumb = (object) [
        'title' => $pelatihan->nama_pelatihan, // Sesuaikan dengan kolom yang benar
        'subtitle' => $bidang ? $bidang->bidang_nama : 'N/A'
    ];

    // Tampilkan view dengan data sertifikasi dan breadcrumb
    return view('bidang.detailPelatihan', [
        'pelatihan' => $pelatihan,
        'breadcrumb' => $breadcrumb,
        'bidang' => $bidang,
        'dosenBidang' => $dosenBidang
    ]);
}

}
