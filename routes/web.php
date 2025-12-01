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

Route::get('/dashboard/spesifikasi', function () {
    return view('dashboard.spesifikasi', [
        'bahan' => \App\Models\JenisBahan::all(),
        'pola' => \App\Models\JenisPola::all(),
        'kerah' => \App\Models\JenisKerah::all(),
        'jahitan' => \App\Models\JenisJahitan::all()
    ]);
});

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
});

Route::get('/dashboard/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dashboard/login', [AuthController::class, 'login']);
Route::post('/dashboard/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('/dashboard/pegawai', PegawaiController::class);
Route::get('/pegawai/casbon', [PegawaiController::class, 'showCasbonForm'])->name('pegawai.casbon.form');

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

Route::post('/spesifikasi/bahan', [JenisBahanController::class, 'store']);
Route::delete('/spesifikasi/bahan/{id}', [JenisBahanController::class, 'destroy']);
Route::put('/spesifikasi/bahan/{id}', [JenisBahanController::class, 'update']);

Route::post('/spesifikasi/pola', [JenisPolaController::class, 'store']);
Route::delete('/spesifikasi/pola/{id}', [JenisPolaController::class, 'destroy']);
Route::put('/spesifikasi/pola/{id}', [JenisPolaController::class, 'update']);

Route::post('/spesifikasi/kerah', [JenisKerahController::class, 'store']);
Route::delete('/spesifikasi/kerah/{id}', [JenisKerahController::class, 'destroy']);
Route::put('/spesifikasi/kerah/{id}', [JenisKerahController::class, 'update']);

Route::post('/spesifikasi/jahitan', [JenisJahitanController::class, 'store']);
Route::delete('/spesifikasi/jahitan/{id}', [JenisJahitanController::class, 'destroy']);
Route::put('/spesifikasi/jahitan/{id}', [JenisJahitanController::class, 'update']);