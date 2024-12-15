<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JabatanModel;
use Illuminate\Http\Request;

class JabatanApiController extends Controller
{
    public function index()
    {
        $jabatan = JabatanModel::all();

        // Jika data jabatan kosong
        if ($jabatan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jabatan tidak ditemukan'
            ], 404);
        }

        // Jika data jabatan tidak kosong
        return response()->json([
            'status' => 'success',
            'data' => $jabatan
        ]);
    }
}
