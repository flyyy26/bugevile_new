<?php

namespace App\Http\Controllers;

use App\Models\KategoriJenisOrder;
use Illuminate\Http\Request;

class KategoriJenisOrderController extends Controller
{
    public function index()
    {
        $kategori = KategoriJenisOrder::all();
        return view('dashboard.orders.setting', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $kategori = KategoriJenisOrder::create([
            'nama' => $request->nama
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriJenisOrder::findOrFail($id);
        
        // Hapus semua jenis spek dan detailnya terlebih dahulu
        foreach ($kategori->jenisSpek as $jenisSpek) {
            // Hapus semua detail jenis spek
            foreach ($jenisSpek->detail as $detail) {
                // Hapus gambar jika ada
                if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                    Storage::delete('public/' . $detail->gambar);
                }
                // Hapus relasi many-to-many
                $detail->jenisOrder()->detach();
                // Hapus detail
                $detail->delete();
            }
            // Hapus jenis spek
            $jenisSpek->delete();
        }
        
        // Hapus kategori itu sendiri
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
