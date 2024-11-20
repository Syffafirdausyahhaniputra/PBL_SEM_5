<?php

use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\Api\ProfileController;


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

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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