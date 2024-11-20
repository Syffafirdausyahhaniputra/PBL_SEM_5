<?php

use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\API\PelatihanController;
use App\Http\Controllers\API\SertifikasiController;

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


Route::middleware('auth:api')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/riwayat', [RiwayatController::class, 'getRiwayatApi']);
Route::get('index', [PelatihanController::class, 'index'])->name('pelatihan.index');
        