<?php

namespace App\Http\Controllers;

use App\Models\HargaAffiliator;
use Illuminate\Http\Request;

class HargaAffiliatorController extends Controller
{
    public function index()
    {
        // Ambil data pertama. Jika kosong, buat object baru agar tidak error di view
        // firstOrNew tidak menyimpan ke DB, hanya membuat instance object
        $harga = HargaAffiliator::firstOrNew([], ['harga' => 0]); 
        
        // Jika data belum tersimpan di DB (masih baru), simpan dulu agar punya ID
        if (!$harga->exists) {
            $harga->save();
        }

        return view('dashboard.pengaturan', compact('harga'));
    }

    // Method Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
        ]);

        $harga = HargaAffiliator::findOrFail($id);
        
        $harga->update([
            'harga' => $request->harga
        ]);
        return redirect()->route('harga.index')
                         ->with('success', 'Harga affiliator berhasil diperbarui.');
    }
}