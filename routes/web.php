<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenissertifController;
use App\Http\Controllers\LevelpelatihanController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\KompetensiProdiController;
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
    Route::get('/welcome2', [WelcomeController::class, 'index2']);

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

    Route::group(['prefix' => 'bidang', 'middleware' => 'authorize:ADMN,LEAD'], function () {
        Route::get('/list2', [BidangController::class, 'index1']);
        Route::get('/', [BidangController::class, 'index']);         // menampilkan halaman awal bidang
        Route::post('/list', [BidangController::class, 'list']);     // menampilkan data bidang dalam bentuk json untuk datables
        Route::get('/create_ajax', [BidangController::class, 'create_ajax']); // Menampilkan halaman form tambah bidang Ajax
        Route::post('/ajax', [BidangController::class, 'store_ajax']);     // Menyimpan data bidang baru Ajax
        Route::get('/{id}/show_ajax', [BidangController::class, 'show_ajax']);       // menampilkan detail bidang
        Route::get('/{id}/edit_ajax', [BidangController::class, 'edit_ajax']); // Menampilkan halaman form edit bidang Ajax
        Route::put('/{id}/update_ajax', [BidangController::class, 'update_ajax']); // Menyimpan perubahan data bidang Ajax
        Route::get('/{id}/delete_ajax', [BidangController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete bidang Ajax
        Route::delete('/{id}/delete_ajax', [BidangController::class, 'delete_ajax']); // Untuk menghapus data bidang Ajax
        Route::get('/import', [BidangController::class, 'import']);
        Route::post('/import_ajax', [BidangController::class, 'import_ajax']);
        Route::get('/export_excel', [BidangController::class, 'export_excel']);
        Route::get('/export_pdf', [BidangController::class, 'export_pdf']);
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

    Route::group(['prefix' => 'matkul', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [MatkulController::class, 'index']);         // menampilkan halaman awal matkul
        Route::post('/list', [MatkulController::class, 'list']);     // menampilkan data matkul dalam bentuk json untuk datatables
        Route::get('/create_ajax', [MatkulController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [MatkulController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [MatkulController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MatkulController::class, 'edit_ajax']); // Menampilkan halaman form edit matkul Ajax
        Route::put('/{id}/update_ajax', [MatkulController::class, 'update_ajax']); // Menyimpan perubahan data matkul Ajax
        Route::get('/{id}/delete_ajax', [MatkulController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete matkul Ajax
        Route::delete('/{id}/delete_ajax', [MatkulController::class, 'delete_ajax']); // Untuk menghapus data matkul Ajax
        Route::get('/import', [MatkulController::class, 'import']);
        Route::post('/import_ajax', [MatkulController::class, 'import_ajax']);
        Route::get('/export_excel', [MatkulController::class, 'export_excel']);
        Route::get('/export_pdf', [MatkulController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'prodi', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [ProdiController::class, 'index']);         // menampilkan halaman awal matkul
        Route::post('/list', [ProdiController::class, 'list']);     // menampilkan data matkul dalam bentuk json untuk datatables
        Route::get('/create_ajax', [ProdiController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [ProdiController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [ProdiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [ProdiController::class, 'edit_ajax']); // Menampilkan halaman form edit matkul Ajax
        Route::put('/{id}/update_ajax', [ProdiController::class, 'update_ajax']); // Menyimpan perubahan data matkul Ajax
        Route::get('/{id}/delete_ajax', [ProdiController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete matkul Ajax
        Route::delete('/{id}/delete_ajax', [ProdiController::class, 'delete_ajax']); // Untuk menghapus data matkul Ajax
        Route::get('/import', [ProdiController::class, 'import']);
        Route::post('/import_ajax', [ProdiController::class, 'import_ajax']);
        Route::get('/export_excel', [ProdiController::class, 'export_excel']);
        Route::get('/export_pdf', [ProdiController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'kompetensi_prodi', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [KompetensiProdiController::class, 'index']);         // menampilkan halaman awal matkul
        Route::post('/list', [KompetensiProdiController::class, 'list']);     // menampilkan data matkul dalam bentuk json untuk datatables
        Route::get('/create_ajax', [KompetensiProdiController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [KompetensiProdiController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [KompetensiProdiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KompetensiProdiController::class, 'edit_ajax']); // Menampilkan halaman form edit matkul Ajax
        Route::put('/{id}/update_ajax', [KompetensiProdiController::class, 'update_ajax']); // Menyimpan perubahan data matkul Ajax
        Route::get('/{id}/delete_ajax', [KompetensiProdiController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete matkul Ajax
        Route::delete('/{id}/delete_ajax', [KompetensiProdiController::class, 'delete_ajax']); // Untuk menghapus data matkul Ajax
        Route::get('/import', [KompetensiProdiController::class, 'import']);
        Route::post('/import_ajax', [KompetensiProdiController::class, 'import_ajax']);
        Route::get('/export_excel', [KompetensiProdiController::class, 'export_excel']);
        Route::get('/export_pdf', [KompetensiProdiController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'jenis', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [JenissertifController::class, 'index']);         // menampilkan halaman awal jenis
        Route::post('/list', [JenissertifController::class, 'list']);     // menampilkan data jenis dalam bentuk json untuk datatables
        Route::get('/create_ajax', [JenissertifController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [JenissertifController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [JenissertifController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [JenissertifController::class, 'edit_ajax']); // Menampilkan halaman form edit jenis Ajax
        Route::put('/{id}/update_ajax', [JenissertifController::class, 'update_ajax']); // Menyimpan perubahan data jenis Ajax
        Route::get('/{id}/delete_ajax', [JenissertifController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete jenis Ajax
        Route::delete('/{id}/delete_ajax', [JenissertifController::class, 'delete_ajax']); // Untuk menghapus data jenis Ajax
        Route::get('/import', [JenissertifController::class, 'import']);
        Route::post('/import_ajax', [JenissertifController::class, 'import_ajax']);
        Route::get('/export_excel', [JenissertifController::class, 'export_excel']);
        Route::get('/export_pdf', [JenissertifController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADMN'], function () {
        Route::get('/', [LevelpelatihanController::class, 'index']);         // menampilkan halaman awal level
        Route::post('/list', [LevelpelatihanController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create_ajax', [LevelpelatihanController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
        Route::post('/ajax', [LevelpelatihanController::class, 'store_ajax']);     // Menyimpan data level baru Ajax
        Route::get('/{id}/show_ajax', [LevelpelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [LevelpelatihanController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax
        Route::put('/{id}/update_ajax', [LevelpelatihanController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::get('/{id}/delete_ajax', [LevelpelatihanController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete level Ajax
        Route::delete('/{id}/delete_ajax', [LevelpelatihanController::class, 'delete_ajax']); // Untuk menghapus data level Ajax
        Route::get('/import', [LevelpelatihanController::class, 'import']);
        Route::post('/import_ajax', [LevelpelatihanController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelpelatihanController::class, 'export_excel']);
        Route::get('/export_pdf', [LevelpelatihanController::class, 'export_pdf']);
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

    Route::group(['prefix' => 'notifikasi', 'middleware' => 'authorize:DOSN'], function () {
        Route::get('/', [NotifikasiController::class, 'index']);
        Route::post('/list', [NotifikasiController::class, 'list']);
        Route::get('/create_ajax', [NotifikasiController::class, 'create_ajax']);
        Route::post('/ajax', [NotifikasiController::class, 'store_ajax']);
        Route::get('/sertifikasi/{id}/show_ajax', [NotifikasiController::class, 'showSertifikasiAjax']);
        Route::get('/pelatihan/{id}/show_ajax', [NotifikasiController::class, 'showPelatihanAjax']);

        Route::get('/{id}/edit_ajax', [NotifikasiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [NotifikasiController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [NotifikasiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [NotifikasiController::class, 'delete_ajax']);
        Route::delete('/{id}', [NotifikasiController::class, 'destroy']);
    });


    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [PelatihanController::class, 'index'])->name('pelatihan.index'); // menampilkan daftar pelatihan
        Route::get('/create', [PelatihanController::class, 'create'])->name('pelatihan.create'); // halaman tambah pelatihan
        Route::post('/store', [PelatihanController::class, 'store'])->name('pelatihan.store'); // simpan data pelatihan
        Route::get('/edit/{id}', [PelatihanController::class, 'edit'])->name('pelatihan.edit'); // halaman edit pelatihan
        Route::put('/update/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update'); // update data pelatihan
        Route::delete('/destroy/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.destroy'); // hapus data pelatihan
    });
    

});
