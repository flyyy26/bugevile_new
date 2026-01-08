<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Update sisa bayar untuk pembayaran tertentu
    public function bayar(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'pembayaran_baru' => 'required|numeric|min:0',
            'sisa_bayar_baru' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $pembayaranBaru = $request->pembayaran_baru;
            $sisaBayarBaru = $request->sisa_bayar_baru;
            
            $pembayaranList = $pelanggan->pembayarans()->where('status', false)->get();
            
            if ($pembayaranList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pembayaran yang belum lunas'
                ], 400);
            }
            
            // Total sisa bayar saat ini
            $totalSisaBayarSaatIni = $pembayaranList->sum('sisa_bayar');
            
            // Validasi
            if ($pembayaranBaru > $totalSisaBayarSaatIni) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak boleh melebihi total sisa bayar'
                ], 400);
            }
            
            if ($sisaBayarBaru != ($totalSisaBayarSaatIni - $pembayaranBaru)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perhitungan sisa bayar tidak valid'
                ], 400);
            }
            
            // Distribusikan pembayaran baru secara proporsional
            foreach ($pembayaranList as $pembayaran) {
                $proportion = $totalSisaBayarSaatIni > 0 ? 
                    ($pembayaran->sisa_bayar / $totalSisaBayarSaatIni) : 0;
                
                $pembayaranUntukItem = $pembayaranBaru * $proportion;
                $sisaBayarItem = $pembayaran->sisa_bayar - $pembayaranUntukItem;
                
                // Update
                $pembayaran->dp += $pembayaranUntukItem;
                $pembayaran->sisa_bayar = $sisaBayarItem;
                $pembayaran->updateStatusAuto();
                $pembayaran->save();
            }
            
            DB::commit();
            
            // Get updated totals
            $pelanggan->refresh();
            $pembayaranList = $pelanggan->pembayarans()->where('status', false)->get();
            $totalDp = $pelanggan->pembayarans()->sum('dp');
            $totalSisaBayar = $pelanggan->pembayarans()->sum('sisa_bayar');
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat',
                'data' => [
                    'pembayaran_baru' => $pembayaranBaru,
                    'pembayaran_baru_formatted' => 'Rp ' . number_format($pembayaranBaru, 0, ',', '.'),
                    'total_dp' => $totalDp,
                    'total_dp_formatted' => 'Rp ' . number_format($totalDp, 0, ',', '.'),
                    'total_sisa_bayar' => $totalSisaBayar,
                    'total_sisa_bayar_formatted' => 'Rp ' . number_format($totalSisaBayar, 0, ',', '.'),
                    'status' => $pembayaranList->isEmpty(),
                    'status_text' => $pembayaranList->isEmpty() ? 'LUNAS' : 'BELUM LUNAS',
                    'status_class' => $pembayaranList->isEmpty() ? 'text-green-600 font-bold' : 'text-red-600 font-bold'
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage(),
                'trace' => env('APP_DEBUG') ? $e->getTrace() : []
            ], 500);
        }
    }

    // Lunasi semua pembayaran pelanggan
    public function lunasiSemua(Request $request, Pelanggan $pelanggan)
    {
        try {
            DB::beginTransaction();
            
            $pembayaranList = $pelanggan->pembayarans()->where('status', false)->get();
            
            foreach ($pembayaranList as $pembayaran) {
                $pembayaran->lunasiSemua();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Semua pembayaran telah dilunasi',
                'redirect' => route('pelanggan.show', $pelanggan->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melunasi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get detail pembayaran untuk edit
    public function getDetail(Pembayaran $pembayaran)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pembayaran->id,
                'dp' => $pembayaran->dp,
                'sisa_bayar' => $pembayaran->sisa_bayar,
                'harus_dibayar' => $pembayaran->harus_dibayar,
                'status' => $pembayaran->status,
                'order_id' => $pembayaran->order_id,
                'order_nama' => optional($pembayaran->order)->nama_job ?? 'N/A'
            ]
        ]);
    }

    public function updateAllForPelanggan(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'sisa_bayar' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $newSisaBayar = $request->sisa_bayar;
            $pembayaranList = $pelanggan->pembayarans()->where('status', false)->get();
            
            if ($pembayaranList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pembayaran yang belum lunas'
                ]);
            }
            
            // Distribute the new sisa bayar evenly or adjust logic as needed
            $oldTotalSisaBayar = $pembayaranList->sum('sisa_bayar');
            $pembayaranDiterima = $oldTotalSisaBayar - $newSisaBayar;
            
            // Update each pembayaran proportionally
            foreach ($pembayaranList as $pembayaran) {
                $proportion = $pembayaran->sisa_bayar / $oldTotalSisaBayar;
                $newIndividualSisa = $newSisaBayar * $proportion;
                $additionalDp = ($pembayaran->sisa_bayar - $newIndividualSisa);
                
                $pembayaran->dp += $additionalDp;
                $pembayaran->sisa_bayar = $newIndividualSisa;
                $pembayaran->updateStatusAuto();
                $pembayaran->save();
            }
            
            DB::commit();
            
            // Get updated totals
            $pelanggan->refresh();
            $pembayaranList = $pelanggan->pembayarans()->where('status', false)->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diupdate',
                'data' => [
                    'total_dp' => $pelanggan->pembayarans()->sum('dp'),
                    'total_sisa_bayar' => $pelanggan->pembayarans()->sum('sisa_bayar'),
                    'status_text' => $pembayaranList->isEmpty() ? 'LUNAS' : 'BELUM LUNAS',
                    'status_class' => $pembayaranList->isEmpty() ? 'text-green-600 font-bold' : 'text-red-600 font-bold'
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal update pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}