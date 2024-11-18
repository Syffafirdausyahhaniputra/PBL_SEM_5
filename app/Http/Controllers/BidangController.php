<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidangModel; // Pastikan model sudah dibuat dan terhubung ke database
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class BidangController extends Controller
{
    public function index()
    {
        // Breadcrumb untuk halaman
        $breadcrumb = (object) [
            'title' => 'Bidang',
            'subtitle' => 'Daftar Semua Bidang'
        ];

        // Ambil semua data dari database
        $bidang = BidangModel::all();

        // Kirimkan ke view
        return view('bidang.index', compact('breadcrumb', 'bidang'));
    }

    public function getData(Request $request)
    {
        // Gunakan DataTables jika diperlukan untuk pemrosesan data dinamis
        if ($request->ajax()) {
            $data = BidangModel::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'bidang_nama' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Simpan data baru
        BidangModel::create([
            'bidang_nama' => $request->bidang_nama
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bidang berhasil ditambahkan.'
        ]);
    }

    public function destroy($id)
    {
        // Hapus data berdasarkan ID
        $bidang = BidangModel::findOrFail($id);
        $bidang->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bidang berhasil dihapus.'
        ]);
    }
}
