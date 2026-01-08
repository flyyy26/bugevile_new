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
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\HargaAffiliatorController;
use App\Http\Controllers\KemampuanProduksiController;

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
        // routes/web.php
        Route::get('/total-transaksi', [DashboardController::class, 'totalTransaksi'])->name('total.transaksi');

        // Progress
        Route::get('/get-progress/{slug}', [DashboardController::class, 'getProgress']);
        Route::post('/progress/store', [DashboardController::class, 'store'])->name('progress.store');

        Route::get('/group-order', [OrderController::class, 'indexGroupOrders'])->name('group-order.index');
        Route::get('/group-order-show/{id}', [OrderController::class, 'showGroupOrder'])->name('group-order.show');
        Route::get('/group-order/export', [OrderController::class, 'exportGroupOrders'])->name('group-order.export');
        Route::post('/group-order-show/{id}/mark-paid', [OrderController::class, 'markAsPaid'])->name('group-order.mark-paid');

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        Route::post('/orders/store-multiple', [OrderController::class, 'storeMultiple'])->name('orders.store.multiple');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders-detail/{slug}', [OrderController::class, 'detail']);
        Route::get('/order-totals/{slug}', [DashboardController::class, 'getOrderTotalsBySlug']);

        // Pengaturan harga
        Route::middleware('role:admin')->prefix('pengaturan')->group(function () {
            Route::get('/', [PengaturanHargaController::class, 'index'])->name('harga.index');
            Route::post('/update-harga', [PengaturanHargaController::class, 'update'])->name('harga.update');

            Route::post('/job', [PengaturanHargaController::class, 'storeJob'])->name('pengaturan.job.store');
            Route::put('/job/{id}', [PengaturanHargaController::class, 'updateJob'])->name('pengaturan.job.update');
            Route::delete('/job/{id}', [PengaturanHargaController::class, 'destroyJob'])->name('pengaturan.job.destroy');

            Route::put('/affiliator/{id}', [HargaAffiliatorController::class, 'update'])->name('pengaturan.affiliator.update');
        });

        // routes/web.php
        Route::post('/pembayaran/update/{pembayaran}', [PembayaranController::class, 'updateSisaBayar'])->name('pembayaran.update');
        Route::post('/pembayaran/lunasi/{pelanggan}', [PembayaranController::class, 'lunasiSemua'])->name('pembayaran.lunasi-semua');

        // Pegawai
        Route::resource('pegawai', PegawaiController::class);
        Route::get('/pegawai/casbon', [PegawaiController::class, 'showCasbonForm'])->name('pegawai.casbon.form');
        Route::get('/pegawai/{pegawai}/casbon', [PegawaiController::class, 'getCasbonHistory'])->name('pegawai.casbon.history');
        Route::post('/pegawai/casbon', [PegawaiController::class, 'storeCasbon'])->name('pegawai.casbon.store');
        Route::delete('/pegawai/casbon/{casbon}', [PegawaiController::class, 'deleteCasbon'])->name('pegawai.casbon.delete');

        // Kemampuan Produksi Routes (SIMPEL)
        Route::put('/kemampuan-produksi', [KemampuanProduksiController::class, 'update'])->name('kemampuan-produksi.update');

        // Pelanggan
        Route::get(
            '/nota/pelanggan/{pelanggan}',
            [PelangganController::class, 'showNotaByPelanggan']
        )->name('pelanggan.nota');
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
            Route::get('/{id}', [JenisOrderController::class, 'show'])->name('jenis-order.show');
            Route::put('/laba/{id}', [JenisOrderController::class, 'updateLaba'])->name('laba.update');
        });

        // Kategori jenis order
        Route::post('/kategori-jenis-order/store', [KategoriJenisOrderController::class, 'store'])->name('kategori-jenis-order.store');
        Route::delete('/kategori-jenis-order/{id}', [KategoriJenisOrderController::class, 'destroy'])->name('kategori-jenis-order.destroy');

        Route::get('/{slug}', [DashboardController::class, 'showBySlug'])->name('dashboard.job');
});

Route::get('/afiliasi', [AffiliatorController::class, 'showLogin'])->name('affiliate.login');
Route::post('/afiliasi', [AffiliatorController::class, 'processLogin'])->name('affiliate.login.process');

Route::get('/afiliasi-detail/{id}', [AffiliatorController::class, 'showDetail'])
    ->middleware('affiliate.auth')
    ->name('afiliasi-detail');

Route::post('/afiliasi-logout', [AffiliatorController::class, 'logout'])->name('affiliate.logout');

Route::get('/lihat-progres', [DashboardController::class, 'lihatProgress'])->name('lihat-progres');
Route::get('/lihat-progres/{slug}', [DashboardController::class, 'showBySlugProgress'])->name('show-lihat-progres');