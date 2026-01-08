<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route API untuk pembayaran
Route::prefix('pembayaran')->group(function () {
    Route::post('/pelanggan/{pelanggan}/bayar', [PembayaranController::class, 'bayar']);
    Route::post('/pelanggan/{pelanggan}/lunasi-semua', [PembayaranController::class, 'lunasiSemua']);
});