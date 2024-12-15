<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GolonganModel;
use Illuminate\Http\Request;

class GolonganApiController extends Controller
{
    public function index()
    {
        $golongan = GolonganModel::all();

        // Jika data golongan kosong
        if ($golongan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data golongan tidak ditemukan'
            ], 404);
        }

        // Jika data golongan tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $golongan
        ]);
    }
}
