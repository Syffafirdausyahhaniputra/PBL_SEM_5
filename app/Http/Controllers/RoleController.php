<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class RoleController extends Controller
{
    // Menampilkan halaman awal role
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manage Jabatan',
            'subtitle'  => 'Daftar jabatan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'role'; // set menu yang sedang aktif

        return view('role.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data role dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $roles = RoleModel::select('role_id', 'role_nama');

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('aksi', function ($role) {
                $btn  = '<button onclick="modalAction(\'' . url('/role/' . $role->role_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/role/' . $role->role_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/role/' . $role->role_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('role.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'role_kode' => 'required|string|min:3|unique:m_role,role_kode',
                'role_nama' => 'required|string|max:100',
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
            RoleModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Jabatan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $role = RoleModel::find($id);

        // Jika role tidak ditemukan
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Jabatan tidak ditemukan'
            ]);
        }

        return view('role.edit_ajax', ['role' => $role]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'role_kode' => 'required|string|max:5|unique:m_role,role_kode,' . $id . ',role_id',
                'role_nama' => 'required|string|max:100',
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

            $role = RoleModel::find($id);
            if ($role) {
                $role->update($request->all());
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
        $role = RoleModel::find($id);
        return view('role.confirm_ajax', ['role' => $role]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $role = RoleModel::find($id);
            if ($role) {
                $role->delete();
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
        // Cari role berdasarkan id
        $role = RoleModel::find($id);

        // Periksa apakah role ditemukan
        if ($role) {
            // Tampilkan halaman show_ajax dengan data role
            return view('role.show_ajax', ['role' => $role]);
        } else {
            // Tampilkan pesan kesalahan jika role tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
