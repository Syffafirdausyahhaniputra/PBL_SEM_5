<?php

use App\Http\Controllers\API\PelatihanController as PelatihanApiController;
use App\Http\Controllers\Api\Dashboard2Controller;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\API\SertifikasiApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\API\SertifikasiController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\API\ProfileDosenController;

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

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
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

Route::get('/kompetensi', [App\Http\Controllers\Api\KompetensiController::class, 'index']);
Route::post('/kompetensi/list', [App\Http\Controllers\Api\KompetensiController::class, 'list']);
Route::get('/kompetensi/{prodi_kode}/show_ajax', [App\Http\Controllers\Api\KompetensiController::class, 'show_ajax']);



Route::get('/riwayat', [RiwayatController::class, 'getRiwayatApi']);
Route::resource('pelatihan', PelatihanController::class);


// Route::middleware('auth:api')->post('/profil', [App\Http\Controllers\Api\ProfileController::class, 'index']);
// Route::middleware('auth:api')->post('/profil/{id}', [App\Http\Controllers\Api\ProfileController::class, 'show']);

// Route::middleware(['auth:api'])->group(function () {
Route::post('/profil', [App\Http\Controllers\Api\ProfileController::class, 'index']);
Route::get('/profil/{id}', [App\Http\Controllers\Api\ProfileController::class, 'show']);
// });


// Route untuk mendapatkan profil pengguna yang sedang login
// Route::middleware('auth:api')->get('/profil', [ProfileController::class, 'index']);

// // Route untuk mendapatkan profil pengguna berdasarkan ID
// Route::middleware('auth:api')->get('/profil/{id}', [ProfileController::class, 'show']);