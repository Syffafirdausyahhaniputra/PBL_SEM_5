<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
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
}
