<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\JenisOrderController;
use App\Http\Controllers\KategoriJenisOrderController;
use App\Http\Controllers\PengaturanHargaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisBahanController;
use App\Http\Controllers\JenisPolaController;
use App\Http\Controllers\JenisKerahController;
use App\Http\Controllers\JenisJahitanController;
use App\Http\Controllers\SpesifikasiController;

Route::get('/dashboard/orders', [OrderController::class, 'index']);
Route::post('/dashboard/orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::delete('/dashboard/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/dashboard/orders-detail/{slug}', [OrderController::class, 'detail']);

Route::get('/dashboard/order-totals/{slug}', [DashboardController::class, 'getOrderTotalsBySlug']);
Route::get('/dashboard/get-progress/{slug}', [DashboardController::class, 'getProgress']);

Route::get('/dashboard/orders-detail', function () {
    return view('dashboard.orders-detail');
});

Route::middleware(['auth'])->prefix('/dashboard/pengaturan')->group(function () {
    Route::get('/', [PengaturanHargaController::class, 'index'])->name('harga.index');
    Route::post('/update/{field}', [PengaturanHargaController::class, 'update'])->name('harga.update');
    // Job CRUD for Pengaturan (create, update, delete)
    Route::post('/job', [PengaturanHargaController::class, 'storeJob'])->name('pengaturan.job.store');
    Route::post('/job/{id}', [PengaturanHargaController::class, 'updateJob'])->name('pengaturan.job.update');
    Route::put('/job/{id}', [PengaturanHargaController::class, 'updateJob']);
    Route::delete('/job/{id}', [PengaturanHargaController::class, 'destroyJob'])->name('pengaturan.job.destroy');
});

Route::get('/dashboard/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dashboard/login', [AuthController::class, 'login']);
Route::post('/dashboard/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('/dashboard/pegawai', PegawaiController::class);
Route::get('/pegawai/casbon', [PegawaiController::class, 'showCasbonForm'])->name('pegawai.casbon.form');

Route::get('/dashboard/spesifikasi', [SpesifikasiController::class, 'index'])
    ->name('spesifikasi.index');

Route::prefix('dashboard/orders/setting')->group(function () {
    Route::get('/', [JenisOrderController::class, 'index'])->name('jenis-order.index');
    Route::post('/', [JenisOrderController::class, 'store'])->name('jenis-order.store');
    Route::delete('/{id}', [JenisOrderController::class, 'destroy'])->name('jenis-order.destroy');
    Route::put('/{id}', [JenisOrderController::class, 'update'])->name('jenis-order.update');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/{slug}', [DashboardController::class, 'showBySlug'])->name('dashboard.job');
Route::get('/dashboard/get-progress/{slug}', [DashboardController::class, 'getProgress']);
Route::post('/progress/store', [DashboardController::class, 'store'])->name('progress.store');
Route::post('/pegawai/casbon', [PegawaiController::class, 'storeCasbon'])->name('pegawai.casbon.store');
Route::post('/kategori-jenis-order/store', [KategoriJenisOrderController::class, 'store'])
    ->name('kategori-jenis-order.store');

Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

Route::post('/jenis-spek/store', [SpesifikasiController::class, 'storeJenisSpek'])->name('jenis_spek.store');
Route::post('/jenis-spek-detail', [SpesifikasiController::class, 'storeJenisSpekDetail'])->name('jenis_spek_detail.store');
Route::put('/jenis-spek-detail/{id}', [SpesifikasiController::class, 'updateJenisSpekDetail'])->name('jenis_spek_detail.update');
Route::delete('/jenis-spek-detail/{id}', [SpesifikasiController::class, 'destroyJenisSpekDetail'])->name('jenis_spek_detail.destroy');
