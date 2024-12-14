<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
use App\Models\DosenBidangModel;
use Illuminate\Http\Request;

class BidangApiController extends Controller
{
    public function index()
    {
        $bidang = BidangModel::all();

        // Jika data bidang kosong
        if ($bidang->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data bidang tidak ditemukan'
            ], 404);
        }

        // Jika data bidang tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $bidang
        ]);
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

    // Tampilkan data dosen
    return response()->json([
        'status' => 'success',
        'bidang' => $bidang,
        'dosen' => $dosen,
    ]);
}
}
