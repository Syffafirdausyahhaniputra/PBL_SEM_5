<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PelatihanApiController;
use App\Http\Controllers\API\Dashboard2Controller;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\SertifikasiApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PelatihanController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SertifikasiController;
use App\Http\Controllers\API\InputSertifController;
use App\Http\Controllers\API\DosenController;
use App\Http\Controllers\API\NotifikasiPimpinanController;
use App\Http\Controllers\API\ProfileDosenController;
use App\Http\Controllers\API\PlthnController;
use App\Http\Controllers\API\BidangApiController;
use App\Http\Controllers\API\GolonganApiController;
use App\Http\Controllers\API\JabatanApiController;
use App\Http\Controllers\API\JenisApiController;
use App\Http\Controllers\API\MataKuliahApiController;
use App\Http\Controllers\API\NotifikasiDosenController;
use App\Http\Controllers\API\PangkatApiController;
use App\Http\Controllers\API\VendorApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/pelatihan', [PelatihanController::class, 'list']);
Route::get('/pelatihan/dropdown', [PelatihanController::class, 'dropdown']);
Route::post('/pelatihan/store', [PelatihanController::class, 'store'])->name('pelatihan.store');
Route::get('create', [PelatihanController::class, 'create'])->name('pelatihan.create');
Route::get('edit/{id}', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
Route::put('update/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');
Route::delete('destroy/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.destroy');

Route::get('/sertifikasi', [SertifikasiController::class, 'index']);
Route::get('/sertifikasi/dropdown', [SertifikasiController::class, 'dropdown']);
Route::post('/sertifikasi/store', [SertifikasiController::class, 'store'])->name('sertifikasi.store');
Route::get('create', [SertifikasiController::class, 'create'])->name('sertifikasi.create');
Route::post('store', [SertifikasiController::class, 'store'])->name('sertifikasi.store');
Route::get('edit/{id}', [SertifikasiController::class, 'edit'])->name('sertifikasi.edit');
Route::put('update/{id}', [SertifikasiController::class, 'update'])->name('sertifikasi.update');
Route::delete('destroy/{id}', [SertifikasiController::class, 'destroy'])->name('sertifikasi.destroy');

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route yang hanya dapat diakses setelah login
Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard2', [Dashboard2Controller::class, 'index']);

    // Route untuk get all data
    Route::get('listData', [DashboardController::class, 'listData'])->name('listData');

    Route::get('/profiledosen', [ProfileDosenController::class, 'index']); // Menampilkan profil dosen
    Route::post('/profiledosen', [ProfileDosenController::class, 'update']); // Mengupdate profil dosen

    Route::get('/profile', [ProfileController::class, 'index']); // Menampilkan profil dosen
    Route::post('/profile', [ProfileController::class, 'update']); // Mengupdate profil dosen

    Route::group(['prefix' => 'sertifikasi'], function () {
        Route::get('/', [SertifikasiApiController::class, 'index']); // Menampilkan data dosen
        Route::post('create', [SertifikasiApiController::class, 'store']); // Menambahkan data dosen
        Route::get('show/{id}', [SertifikasiApiController::class, 'show']); // Menampilkan data dosen berdasarkan ID
        Route::post('update/{id}', [SertifikasiApiController::class, 'update']); // Mengupdate data dosen berdasarkan ID
    });

    Route::get('jenis', [SertifikasiApiController::class, 'getJenis']);
    Route::get('bidang', [SertifikasiApiController::class, 'getBidang']);


    // Route untuk bidang
    Route::group(['prefix' => 'bidang'], function () {
        Route::get('/', [BidangApiController::class, 'index']); // Menampilkan data bidang
        Route::post('create', [BidangApiController::class, 'store']); // Menambahkan data bidang
        Route::get('show/{id}', [BidangApiController::class, 'show']); // Menampilkan data bidang berdasarkan ID
        Route::post('update/{id}', [BidangApiController::class, 'update']); // Mengupdate data bidang berdasarkan ID
        Route::get('infodosen/{id}/{id_dosen}', [BidangApiController::class, 'showDosen']);
    });

    Route::group(['prefix' => 'golongan'], function () {
        Route::get('/', [GolonganApiController::class, 'index']);
        Route::post('create', [GolonganApiController::class, 'store']);
        Route::get('show/{id}', [GolonganApiController::class, 'show']);
        Route::post('update/{id}', [GolonganApiController::class, 'update']);
    });

    Route::group(['prefix' => 'pangkat'], function () {
        Route::get('/', [PangkatApiController::class, 'index']);
        Route::post('create', [PangkatApiController::class, 'store']);
        Route::get('show/{id}', [PangkatApiController::class, 'show']);
        Route::post('update/{id}', [PangkatApiController::class, 'update']);
    });

    Route::group(['prefix' => 'jabatan'], function () {
        Route::get('/', [JabatanApiController::class, 'index']);
        Route::post('create', [JabatanApiController::class, 'store']);
        Route::get('show/{id}', [JabatanApiController::class, 'show']);
        Route::post('update/{id}', [JabatanApiController::class, 'update']);
    });

    // Route untuk mata kuliah
    Route::group(['prefix' => 'matakuliah'], function () {
        Route::get('/', [MataKuliahApiController::class, 'index']); // Menampilkan data mata kuliah
        Route::post('create', [MataKuliahApiController::class, 'store']); // Menambahkan data mata kuliah
        Route::get('show/{id}', [MataKuliahApiController::class, 'showDosenByBidang']); // Menampilkan data mata kuliah berdasarkan ID
        Route::post('update/{id}', [MataKuliahApiController::class, 'update']); // Mengupdate data mata kuliah berdasarkan ID
    });

    //Route untuk Jenis Sertifikasi
    Route::group(['prefix' => 'jenis'], function () {
        Route::get('/', [JenisApiController::class, 'index']);
        Route::post('create', [JenisApiController::class, 'store']);
        Route::get('show/{id}', [JenisApiController::class, 'show']);
        Route::post('update/{id}', [JenisApiController::class, 'update']);
    });

    //Route untuk Vendor Sertifikasi
    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/', [VendorApiController::class, 'index']);
        Route::post('create', [VendorApiController::class, 'store']);
        Route::get('show/{id}', [VendorApiController::class, 'show']);
        Route::post('update/{id}', [VendorApiController::class, 'update']);
    });

    Route::group(['prefix' => 'notifikasiPimpinan'], function () {
        Route::get('/list', [NotifikasiPimpinanController::class, 'list']);
        Route::get('/show/sertifikasi/{id}', [NotifikasiPimpinanController::class, 'showSertifikasiApi']);
        Route::get('/show/pelatihan/{id}', [NotifikasiPimpinanController::class, 'showPelatihanApi']);
        Route::put('/verify/{type}/{id}', [NotifikasiPimpinanController::class, 'verify']);
    });

    Route::group(['prefix' => 'notifikasiDosen'], function () {
        Route::get('/list', [NotifikasiDosenController::class, 'list']);
        Route::get('/show/sertifikasi/{id}', [NotifikasiDosenController::class, 'showSertifikasiApi']);
        Route::get('/show/pelatihan/{id}', [NotifikasiDosenController::class, 'showPelatihanApi']);
    });

    Route::group(['prefix' => 'pelatihan'], function () {
        Route::get('/', [PelatihanApiController::class, 'index']); // Menampilkan data dosen
        Route::post('create', [PelatihanApiController::class, 'store']); // Menambahkan data dosen
        Route::get('show/{id}', [PelatihanApiController::class, 'show']); // Menampilkan data dosen berdasarkan ID
        Route::post('update/{id}', [PelatihanApiController::class, 'update']); // Mengupdate data dosen berdasarkan ID
    });

    // Route untuk get all data
    Route::get('listData', [Dashboard2Controller::class, 'listData'])->name('listData');

    Route::get('listData', [DashboardController::class, 'listData'])->name('listData');


    Route::get('/profile', [ProfileController::class, 'index']); // Menampilkan profil dosen
    Route::patch('/profile', [ProfileController::class, 'update']); // Mengupdate profil dosen  
});

// Route::group(['prefix' => 'inputsertifikasi'], function () {
//     Route::get('/', [InputSertifController::class, 'index']);
//     Route::get('/sertifikasi/dropdown', [InputSertifController::class, 'getDropdownOptions']);
//     Route::post('/sertifikasi/create', [InputSertifController::class, 'store']);
//     Route::get('/show/{id}', [InputSertifController::class, 'show']);
//     Route::put('/update/{id}', [InputSertifController::class, 'update']);
// });

Route::get('/kompetensi', [App\Http\Controllers\API\KompetensiController::class, 'index']);
Route::post('/kompetensi/list', [App\Http\Controllers\API\KompetensiController::class, 'list']);
Route::get('/kompetensi/{prodi_id}/show_ajax', [App\Http\Controllers\API\KompetensiController::class, 'show_ajax']);

// Route::group(['prefix' => 'plthn'], function () {
//     Route::get('/', [PlthnController::class, 'index']); // Menampilkan daftar pelatihan
//     Route::get('/dropdown', [PlthnController::class, 'getDropdownOptions']); // Dropdown data
//     Route::post('/create', [PlthnController::class, 'store']); // Menambahkan pelatihan baru
// });



// Route::get('/riwayat', [RiwayatController::class, 'getRiwayatApi']);
// Route::resource('pelatihan', PelatihanController::class);

Route::post('/profil', [App\Http\Controllers\API\ProfileController::class, 'index']);
Route::get('/profil/{id}', [App\Http\Controllers\API\ProfileController::class, 'show']);

// Route::get('/pelatihan/dropdown', [PlthnController::class, 'dropdown']);
// Route::post('/pelatihan/store', [PlthnController::class, 'store'])->name('pelatihan.store');