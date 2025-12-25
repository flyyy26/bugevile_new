<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AffiliatorController;
use App\Http\Controllers\HargaAffiliatorController;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : redirect('/dashboard/login');
});

Route::get('/dashboard/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dashboard/login', [AuthController::class, 'login']);
Route::post('/dashboard/logout', [AuthController::class, 'logout']);

Route::middleware('auth')
    ->prefix('dashboard')
    ->group(function () {

        // Dashboard utama
        Route::get('/', [DashboardController::class, 'index']);

        // Progress
        Route::get('/lihat-progres', [DashboardController::class, 'lihatProgress'])->name('lihat-progres');
        Route::get('/lihat-progres/{slug}', [DashboardController::class, 'showBySlugProgress'])->name('show-lihat-progres');
        Route::get('/get-progress/{slug}', [DashboardController::class, 'getProgress']);
        Route::post('/progress/store', [DashboardController::class, 'store'])->name('progress.store');

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders-detail/{slug}', [OrderController::class, 'detail']);
        Route::get('/order-totals/{slug}', [DashboardController::class, 'getOrderTotalsBySlug']);

        // Pengaturan harga
        Route::prefix('pengaturan')->group(function () {
            Route::get('/', [PengaturanHargaController::class, 'index'])->name('harga.index');
            Route::post('/update-harga', [PengaturanHargaController::class, 'update'])->name('harga.update');

            Route::post('/job', [PengaturanHargaController::class, 'storeJob'])->name('pengaturan.job.store');
            Route::put('/job/{id}', [PengaturanHargaController::class, 'updateJob'])->name('pengaturan.job.update');
            Route::delete('/job/{id}', [PengaturanHargaController::class, 'destroyJob'])->name('pengaturan.job.destroy');

            Route::put('/affiliator/{id}', [HargaAffiliatorController::class, 'update'])->name('pengaturan.affiliator.update');
        });

        // Pegawai
        Route::resource('pegawai', PegawaiController::class);
        Route::get('/pegawai/casbon', [PegawaiController::class, 'showCasbonForm'])->name('pegawai.casbon.form');
        Route::get('/pegawai/{pegawai}/casbon', [PegawaiController::class, 'getCasbonHistory'])->name('pegawai.casbon.history');
        Route::post('/pegawai/casbon', [PegawaiController::class, 'storeCasbon'])->name('pegawai.casbon.store');
        Route::delete('/pegawai/casbon/{casbon}', [PegawaiController::class, 'deleteCasbon'])->name('pegawai.casbon.delete');

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);

        // Affiliator dashboard
        Route::resource('affiliator', AffiliatorController::class);

        // Spesifikasi
        Route::get('/spesifikasi', [SpesifikasiController::class, 'index'])->name('spesifikasi.index');
        Route::post('/jenis-spek/store', [SpesifikasiController::class, 'storeJenisSpek'])->name('jenis_spek.store');
        Route::post('/jenis-spek-detail', [SpesifikasiController::class, 'storeJenisSpekDetail'])->name('jenis_spek_detail.store');
        Route::put('/jenis-spek-detail/{id}', [SpesifikasiController::class, 'updateJenisSpekDetail'])->name('jenis_spek_detail.update');
        Route::delete('/jenis-spek-detail/{id}', [SpesifikasiController::class, 'destroyJenisSpekDetail'])->name('jenis_spek_detail.destroy');
        Route::delete('/jenis-spek/{id}', [SpesifikasiController::class, 'destroyJenisSpek'])->name('jenis_spek.destroy');

        // Setting jenis order
        Route::prefix('orders/setting')->group(function () {
            Route::get('/', [JenisOrderController::class, 'index'])->name('jenis-order.index');
            Route::post('/', [JenisOrderController::class, 'store'])->name('jenis-order.store');
            Route::put('/{id}', [JenisOrderController::class, 'update'])->name('jenis-order.update');
            Route::delete('/{id}', [JenisOrderController::class, 'destroy'])->name('jenis-order.destroy');
        });

        // Kategori jenis order
        Route::post('/kategori-jenis-order/store', [KategoriJenisOrderController::class, 'store'])->name('kategori-jenis-order.store');
        Route::delete('/kategori-jenis-order/{id}', [KategoriJenisOrderController::class, 'destroy'])->name('kategori-jenis-order.destroy');
});

Route::get('/afiliasi', [AffiliatorController::class, 'showLogin'])->name('affiliate.login');
Route::post('/afiliasi', [AffiliatorController::class, 'processLogin'])->name('affiliate.login.process');

Route::get('/afiliasi-detail/{id}', [AffiliatorController::class, 'showDetail'])
    ->middleware('affiliate.auth')
    ->name('afiliasi-detail');

Route::post('/afiliasi-logout', [AffiliatorController::class, 'logout'])->name('affiliate.logout');