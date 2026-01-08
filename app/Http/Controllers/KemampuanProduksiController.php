<?php

namespace App\Http\Controllers;

use App\Models\KemampuanProduksi;
use Illuminate\Http\Request;

class KemampuanProduksiController extends Controller
{
    public function index()
    {
        // Ambil data dari database (bukan create)
        $print = KemampuanProduksi::where('nama_kemampuan', 'Print')->first();
        $packingFinishing = KemampuanProduksi::where('nama_kemampuan', 'Packing & Finishing')->first();
        
        // Jika tidak ada data, buat dengan nilai default
        if (!$print) {
            $print = KemampuanProduksi::create([
                'nama_kemampuan' => 'Print',
                'nilai_kemampuan' => 30
            ]);
        }
        
        if (!$packingFinishing) {
            $packingFinishing = KemampuanProduksi::create([
                'nama_kemampuan' => 'Packing & Finishing',
                'nilai_kemampuan' => 25
            ]);
        }
        
        return view('dashboard.pengaturan', compact('print', 'packingFinishing'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'print' => 'required|integer|min:0',
            'packing_finishing' => 'required|integer|min:0'
        ]);
        
        try {
            // Update Print
            KemampuanProduksi::updateOrCreate(
                ['nama_kemampuan' => 'Print'],
                ['nilai_kemampuan' => $request->print]
            );
            
            // Update Packing & Finishing
            KemampuanProduksi::updateOrCreate(
                ['nama_kemampuan' => 'Packing & Finishing'],
                ['nilai_kemampuan' => $request->packing_finishing]
            );
            
            return back()->with('success', 'Kemampuan produksi berhasil diperbarui');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }
}