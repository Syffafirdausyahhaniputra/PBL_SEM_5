<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\VendorController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Monolog\role;

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

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [UserController::class, 'index']);         // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);     // menampilkan data user dalam bentuk json untuk datables
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);       // menampilkan detail user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk menghapus data user Ajax
        Route::get('/import', [UserController::class, 'import']);
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'role', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [RoleController::class, 'index']);         // menampilkan halaman awal role
        Route::post('/list', [RoleController::class, 'list']);     // menampilkan data role dalam bentuk json untuk datatables
        Route::get('/create_ajax', [RoleController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [RoleController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [RoleController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [RoleController::class, 'edit_ajax']); // Menampilkan halaman form edit role Ajax
        Route::put('/{id}/update_ajax', [RoleController::class, 'update_ajax']); // Menyimpan perubahan data role Ajax
        Route::get('/{id}/delete_ajax', [RoleController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete role Ajax
        Route::delete('/{id}/delete_ajax', [RoleController::class, 'delete_ajax']); // Untuk menghapus data role Ajax
        Route::get('/import', [RoleController::class, 'import']);
        Route::post('/import_ajax', [RoleController::class, 'import_ajax']);
        Route::get('/export_excel', [RoleController::class, 'export_excel']);
        Route::get('/export_pdf', [RoleController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'vendor', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [VendorController::class, 'index']);         // menampilkan halaman awal vendor
        Route::post('/list', [VendorController::class, 'list']);     // menampilkan data vendor dalam bentuk json untuk datatables
        Route::get('/create_ajax', [VendorController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [VendorController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [VendorController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [VendorController::class, 'edit_ajax']); // Menampilkan halaman form edit vendor Ajax
        Route::put('/{id}/update_ajax', [VendorController::class, 'update_ajax']); // Menyimpan perubahan data vendor Ajax
        Route::get('/{id}/delete_ajax', [VendorController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete vendor Ajax
        Route::delete('/{id}/delete_ajax', [VendorController::class, 'delete_ajax']); // Untuk menghapus data vendor Ajax
        Route::get('/import', [VendorController::class, 'import']);
        Route::post('/import_ajax', [VendorController::class, 'import_ajax']);
        Route::get('/export_excel', [VendorController::class, 'export_excel']);
        Route::get('/export_pdf', [VendorController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'riwayat'], function () {
        Route::get('/', [RiwayatController::class, 'index']);
        Route::post('/list', [RiwayatController::class, 'list']);
        Route::get('/create_ajax', [RiwayatController::class, 'create_ajax']);
        Route::post('/ajax', [RiwayatController::class, 'store_ajax']);
        Route::get('/sertifikasi/{id}/show_ajax', [RiwayatController::class, 'showSertifikasiAjax']);
        Route::get('/pelatihan/{id}/show_ajax', [RiwayatController::class, 'showPelatihanAjax']);

        Route::get('/{id}/edit_ajax', [RiwayatController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [RiwayatController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [RiwayatController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [RiwayatController::class, 'delete_ajax']);
        Route::delete('/{id}', [RiwayatController::class, 'destroy']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });
});
