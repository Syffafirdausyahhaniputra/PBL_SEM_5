<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PangkatModel;
use Illuminate\Http\Request;

class PangkatApiController extends Controller
{
    public function index()
    {
        $pangkat = PangkatModel::all();

        // Jika data pangkat kosong
        if ($pangkat->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pangkat tidak ditemukan'
            ], 404);
        }

        // Jika data pangkat tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $pangkat
        ]);
    }
}
