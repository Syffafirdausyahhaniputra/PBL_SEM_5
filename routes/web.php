<?php

use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Monolog\Level;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+'); // Artinya: Ketika ada parameter {id}, maka harus berupa angka

Route::get('/', [HomeController::class, 'index']);

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

// Group route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/welcome', [WelcomeController::class, 'index']);

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [UserController::class, 'index']);         // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);     // menampilkan data user dalam bentuk json untuk datables
        Route::get('/create', [UserController::class, 'create']);  // menampilkan halaman form tambah user
        Route::post('/', [UserController::class, 'store']);        // menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);       // menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
        Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk menghapus data user Ajax
        Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
        Route::get('/import', [UserController::class, 'import']);
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'notifikasi'], function () {
        Route::get('/', [NotifikasiController::class, 'index']);         // menampilkan halaman awal level
        Route::post('/list', [NotifikasiController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create', [NotifikasiController::class, 'create']);  // menampilkan halaman form tambah level
        Route::post('/', [NotifikasiController::class, 'store']);        // menyimpan data level baru
        Route::get('/create_ajax', [NotifikasiController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [NotifikasiController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}', [NotifikasiController::class, 'show']);      // menampilkan detail level
        Route::get('/{id}/show_ajax', [NotifikasiController::class, 'show_ajax']);
        Route::get('/{id}/edit', [NotifikasiController::class, 'edit']); // menampilkan halaman form edit level
        Route::put('/{id}', [NotifikasiController::class, 'update']);    // menyimpan perubahan data level
        Route::get('/{id}/edit_ajax', [NotifikasiController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax
        Route::put('/{id}/update_ajax', [NotifikasiController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::get('/{id}/delete_ajax', [NotifikasiController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete level Ajax
        Route::delete('/{id}/delete_ajax', [NotifikasiController::class, 'delete_ajax']); // Untuk menghapus data level Ajax
        Route::delete('/{id}', [NotifikasiController::class, 'destroy']); // menghapus data level
        Route::get('/import', [NotifikasiController::class, 'import']);
        Route::post('/import_ajax', [NotifikasiController::class, 'import_ajax']);
        Route::get('/export_excel', [NotifikasiController::class, 'export_excel']);
        Route::get('/export_pdf', [NotifikasiController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'riwayat'], function () {
        Route::get('/', [RiwayatController::class, 'index']);
        Route::post('/list', [RiwayatController::class, 'list']);
        Route::get('/create', [RiwayatController::class, 'create']);
        Route::post('/', [RiwayatController::class, 'store']);
        Route::get('/create_ajax', [RiwayatController::class, 'create_ajax']);
        Route::post('/ajax', [RiwayatController::class, 'store_ajax']);
        Route::get('/{id}', [RiwayatController::class, 'show']);
        Route::get('/{id}/show_ajax', [RiwayatController::class, 'show_ajax']);
        Route::get('/{id}/edit', [RiwayatController::class, 'edit']);
        Route::put('/{id}', [RiwayatController::class, 'update']);
        Route::get('/{id}/edit_ajax', [RiwayatController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [RiwayatController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [RiwayatController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [RiwayatController::class, 'delete_ajax']);
        Route::delete('/{id}', [RiwayatController::class, 'destroy']);
        Route::get('/import', [RiwayatController::class, 'import']);
        Route::post('/import_ajax', [RiwayatController::class, 'import_ajax']);
        Route::get('/export_excel', [RiwayatController::class, 'export_excel']);
        Route::get('/export_pdf', [RiwayatController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });
});
