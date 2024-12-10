<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VendorModel;
use Illuminate\Http\Request;

class VendorApiController extends Controller
{
    public function index()
    {
        $vendor = VendorModel::all();

        // Jika data matakuliah kosong
        if ($vendor->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data matakuliah tidak ditemukan'
            ], 404);
        }

        // Jika data matakuliah tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $vendor
        ]);
    }
}
