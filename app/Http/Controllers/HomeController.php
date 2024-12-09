<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DosenModel;

class HomeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'subtitle' => 'di JTI Certivy',
        ];

        $activeMenu = 'home';

        // Ambil data dosen beserta relasinya (user dan bidang)
        $dosenList = DosenModel::with(['user', 'dosenBidang.bidang'])->get();

        return view('home', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'dosenList' => $dosenList,
        ]);
    }
}
