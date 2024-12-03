<?php

use App\Http\Controllers\API\PelatihanApiController;
use App\Http\Controllers\Api\Dashboard2Controller;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\API\SertifikasiApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\API\SertifikasiController;
use App\Http\Controllers\API\InputSertifController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\LoginController as ApiLoginController;
use App\Http\Controllers\API\NotifikasiPimpinanController;
use App\Http\Controllers\API\ProfileDosenController;
use App\Http\Controllers\Api\PlthnController;


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
Route::get('create', [PelatihanController::class, 'create'])->name('pelatihan.create');
Route::post('store', [PelatihanController::class, 'store'])->name('pelatihan.store');
Route::get('edit/{id}', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
Route::put('update/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');
Route::delete('destroy/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.destroy');

Route::get('/sertifikasi', [SertifikasiController::class, 'index']);
Route::get('create', [SertifikasiController::class, 'create'])->name('sertifikasi.create');
Route::post('store', [SertifikasiController::class, 'store'])->name('sertifikasi.store');
Route::get('edit/{id}', [SertifikasiController::class, 'edit'])->name('sertifikasi.edit');
Route::put('update/{id}', [SertifikasiController::class, 'update'])->name('sertifikasi.update');
Route::delete('destroy/{id}', [SertifikasiController::class, 'destroy'])->name('sertifikasi.destroy');

Route::post('/login',ApiLoginController::class)->name('login');
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
    Route::patch('/profiledosen', [ProfileDosenController::class, 'update']); // Mengupdate profil dosen

    Route::group(['prefix' => 'sertifikasi'], function () {
        Route::get('/', [SertifikasiApiController::class, 'index']); // Menampilkan data dosen
        Route::post('create', [SertifikasiApiController::class, 'store']); // Menambahkan data dosen
        Route::get('show/{id}', [SertifikasiApiController::class, 'show']); // Menampilkan data dosen berdasarkan ID
        Route::post('update/{id}', [SertifikasiApiController::class, 'update']); // Mengupdate data dosen berdasarkan ID
    });
    
    Route::group(['prefix' => 'notifikasi'], function () {
        Route::get('/list', [NotifikasiPimpinanController::class, 'list']); 
        Route::get('/show/{type}/{id}', [NotifikasiPimpinanController::class, 'show']); 
        Route::post('/verify/{type}/{id}', [NotifikasiPimpinanController::class, 'verify']); 
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

Route::group(['prefix' => 'sertifikasi'], function () {
    Route::post('/store', [InputSertifController::class, 'store']); // Endpoint untuk menyimpan data
    Route::get('/list', [InputSertifController::class, 'list']);   // Endpoint untuk mendapatkan daftar data
});
Route::get('/kompetensi', [App\Http\Controllers\Api\KompetensiController::class, 'index']);
Route::post('/kompetensi/list', [App\Http\Controllers\Api\KompetensiController::class, 'list']);
Route::get('/kompetensi/{prodi_kode}/show_ajax', [App\Http\Controllers\Api\KompetensiController::class, 'show_ajax']);

Route::group(['prefix' => 'plthn'], function () {
    Route::get('/', [PlthnController::class, 'index']); // Menampilkan daftar pelatihan
    Route::get('/dropdown', [PlthnController::class, 'getDropdownOptions']); // Dropdown data
    Route::post('/create', [PlthnController::class, 'store']); // Menambahkan pelatihan baru
});



Route::get('/riwayat', [RiwayatController::class, 'getRiwayatApi']);
Route::resource('pelatihan', PelatihanController::class);

