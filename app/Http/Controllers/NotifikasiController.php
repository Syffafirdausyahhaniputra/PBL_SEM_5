<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiModel;
use App\Models\DataPelatihanModel;
use Yajra\DataTables\Facades\DataTables;

class NotifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'subtitle'  => ' '
        ];

        $activeMenu = 'notifikasi';

        return view('notifikasi.dosen.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data dalam bentuk json untuk datatables 
    public function list(Request $request)
{
    $dataSertifikasi = DataSertifikasiModel::with('sertif')
        ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->sertif->nama_sertif,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'type' => 'sertifikasi' // Tambahkan type sertifikasi
            ];
        });

    $dataPelatihan = DataPelatihanModel::with('pelatihan')
        ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->pelatihan->nama_pelatihan,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'type' => 'pelatihan' // Tambahkan type pelatihan
            ];
        });

    // Gabungkan data sertifikasi dan pelatihan
    $data = $dataSertifikasi->merge($dataPelatihan);

    return DataTables::of($data)
        ->addIndexColumn()
        ->make(true);
}


public function showSertifikasiAjax($id)
{
    $sertifikasi = DataSertifikasiModel::with(['sertif', 'sertif.bidang', 'sertif.matkul', 'sertif.vendor', 'sertif.jenis'])
        ->findOrFail($id);

    return view('notifikasi.show_ajax', [
        'nama' => $sertifikasi->sertif->nama_sertif,
        'bidang' => $sertifikasi->sertif->bidang->bidang_nama,
        'matkul' => $sertifikasi->sertif->mk_nama,
        'vendor' => $sertifikasi->sertif->vendor->vendor_nama,
        'jenis' => $sertifikasi->sertif->jenis->jenis_nama,
        'tanggal_acara' => $sertifikasi->tanggal,
        'berlaku_hingga' => $sertifikasi->masa_berlaku,
        'periode' => $sertifikasi->periode
    ]);
}

public function showPelatihanAjax($id)
{
    $pelatihan = DataPelatihanModel::with(['pelatihan', 'pelatihan.bidang', 'pelatihan.matkul', 'pelatihan.vendor', 'pelatihan.level'])
        ->findOrFail($id);

    return view('notifikasi.show_ajax', [
        'nama' => $pelatihan->pelatihan->nama_pelatihan,
        'bidang' => $pelatihan->pelatihan->bidang->bidang_nama,
        'matkul' => $pelatihan->pelatihan->mk_nama,
        'vendor' => $pelatihan->pelatihan->vendor->vendor_nama,
        'level' => $pelatihan->pelatihan->level->level_nama,
        'tanggal_acara' => $pelatihan->tanggal,
        'kuota' => $pelatihan->kuota,
        'lokasi' => $pelatihan->lokasi,
        'periode' => $pelatihan->periode
    ]);
}

public function getNotifikasiApi()
{
    $dataSertifikasi = DataSertifikasiModel::with('sertif')
        ->select('data_sertif_id as id', 'keterangan', 'status', 'sertif_id', 'dosen_id')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->sertif->nama_sertif,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'type' => 'sertifikasi' // Tambahkan type sertifikasi
            ];
        });

    $dataPelatihan = DataPelatihanModel::with('pelatihan')
        ->select('data_pelatihan_id as id', 'keterangan', 'status', 'pelatihan_id', 'dosen_id')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->pelatihan->nama_pelatihan,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'type' => 'pelatihan' // Tambahkan type pelatihan
            ];
        });

    // Gabungkan data sertifikasi dan pelatihan
    $data = $dataSertifikasi->merge($dataPelatihan);

    return response()->json($data, 200);
}

}
