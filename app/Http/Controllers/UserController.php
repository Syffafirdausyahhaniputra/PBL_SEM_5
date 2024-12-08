<?php

namespace App\Http\Controllers;

use App\Models\BidangModel;
use App\Models\DosenModel;
use App\Models\MatkulModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage User',
            'subtitle'  => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $role = RoleModel::all(); // ambil data role untuk filter role

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'role' => $role,
            'activeMenu' => $activeMenu
        ]);
    }
    // Ambil data user dalam bentuk json untuk datatables  
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'nip', 'role_id')
            ->with('role');

        // Filter data user berdasarkan role_id 
        if ($request->role_id) {
            $users->where('role_id', $request->role_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn  = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Hapus
                </button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        $role = RoleModel::select('role_id', 'role_nama')->get();

        return view('user.create_ajax')
            ->with('role', $role);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'role_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'nip' => 'required|string|max:100',
                'email' => 'required|string|max:100',
                'password' => 'required|min:6'
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

            // Simpan data ke tabel m_user
            $user = UserModel::create($request->only(['role_id', 'username', 'nama', 'nip', 'email', 'password']));

            // Simpan data ke tabel m_dosen jika role_id adalah 3
            if ($request->role_id == 3) {
                DosenModel::create([
                    'user_id' => $user->user_id,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $role = RoleModel::select('role_id', 'role_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'role' => $role]);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'role_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama'     => 'required|max:100',
                'nip'     => 'required|max:100',
                'email'     => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];
            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = UserModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request 
                    $request->request->remove('password');
                }

                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                // Periksa apakah role_id adalah 3
                if ($user->role_id == 3) {
                    // Hapus data di tabel m_dosen berdasarkan user_id
                    DosenModel::where('user_id', $user->user_id)->delete();
                }

                // Hapus data di tabel m_user
                $user->delete();

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
        // Cari user berdasarkan id
        $user = UserModel::find($id);

        // Periksa apakah user ditemukan
        if ($user) {
            // Tampilkan halaman show_ajax dengan data user
            return view('user.show_ajax', ['user' => $user]);
        } else {
            // Tampilkan pesan kesalahan jika user tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // Validasi file harus xlsx dan maksimal 1MB
                'file_user' => ['required', 'mimes:xlsx', 'max:2048']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_user'); // Ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load reader file excel
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data excel

            $insert = [];
            $dosenInsert = []; // Data untuk tabel dosen

            if (count($data) > 1) { // Jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Baris ke-1 adalah header, maka lewati
                        $hashedPassword = Hash::make($value['F']); // Hash password
                        $insertData = [
                            'role_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'nip' => $value['D'],
                            'email' => $value['E'],
                            'password' => $hashedPassword,
                            'created_at' => now(),
                        ];
                        $insert[] = $insertData;

                        // Tambahkan ke tabel Dosen jika role_id adalah 3
                        if ((int)$value['A'] === 3) {
                            $dosenInsert[] = [
                                'username' => $value['B'], // Asumsikan ini dapat digunakan untuk mapping
                                'created_at' => now(),
                            ];
                        }
                    }
                }

                if (count($insert) > 0) {
                    // Insert data ke tabel user
                    userModel::insertOrIgnore($insert);

                    // Ambil user_id berdasarkan username untuk setiap dosen dan masukkan ke tabel dosen
                    if (!empty($dosenInsert)) {
                        foreach ($dosenInsert as $dosenData) {
                            $user = userModel::where('username', $dosenData['username'])->first();
                            if ($user) {
                                DosenModel::create([
                                    'user_id' => $user->user_id, // Menggunakan user_id dari tabel user
                                    'created_at' => now(),
                                ]);
                            }
                        }
                    }
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
}
