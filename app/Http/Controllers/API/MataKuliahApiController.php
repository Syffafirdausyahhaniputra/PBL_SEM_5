<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MatkulModel;
use Illuminate\Http\Request;

class MataKuliahApiController extends Controller
{
    public function index()
    {
        $matakuliah = MatkulModel::all();

        // Jika data matakuliah kosong
        if ($matakuliah->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data matakuliah tidak ditemukan'
            ], 404);
        }

        // Jika data matakuliah tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $matakuliah
        ]);
    }
}
