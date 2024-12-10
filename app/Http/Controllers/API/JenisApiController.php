<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisModel;
use Illuminate\Http\Request;

class JenisApiController extends Controller
{
    public function index()
    {
        $jenis = JenisModel::all();

        // Jika data matakuliah kosong
        if ($jenis->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data matakuliah tidak ditemukan'
            ], 404);
        }

        // Jika data matakuliah tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ]);
    }
}
