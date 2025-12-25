<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSpek;
use App\Models\JenisSpekDetail;
use App\Models\KategoriJenisOrder;
use Illuminate\Support\Facades\Storage;

class SpesifikasiController extends Controller
{
    public function index()
    {
        // Ambil kategori + speknya
        $kategori = KategoriJenisOrder::with('jenisSpek.detail')->get();
        
        // Ambil semua jenis_order dengan relasi kategorinya untuk ditampilkan di modal
        $jenisOrderList = \App\Models\JenisOrder::all();

        return view('dashboard.spesifikasi', compact('kategori', 'jenisOrderList'));
    }

    public function storeJenisSpek(Request $request)
    {
        $request->validate([
            'nama_jenis_spek' => 'required|string|max:255',
            'id_kategori_jenis_order' => 'required|exists:kategori_jenis_order,id'
        ]);

        JenisSpek::create([
            'nama_jenis_spek' => $request->nama_jenis_spek,
            'id_kategori_jenis_order' => $request->id_kategori_jenis_order
        ]);

        return back()->with('success', 'Jenis Spek berhasil ditambahkan');
    }

    public function destroyJenisSpek(Request $request, $id)
    {
        $jenisSpek = JenisSpek::findOrFail($id);
        
        // Hapus semua detail terkait
        foreach ($jenisSpek->detail as $detail) {
            // Hapus gambar jika ada
            if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                Storage::delete('public/' . $detail->gambar);
            }
            // Hapus relasi many-to-many
            $detail->jenisOrder()->detach();
        }
        
        // Hapus semua detail
        $jenisSpek->detail()->delete();
        
        // Hapus jenis spek itu sendiri
        $jenisSpek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis Spek berhasil dihapus'
        ]);
    }

    public function storeJenisSpekDetail(Request $request)
    {
        $request->validate([
            'nama_jenis_spek_detail' => 'required|string|max:255',
            'id_jenis_spek' => 'required|exists:jenis_spek,id',
            'id_jenis_order' => 'nullable|array',
            'id_jenis_order.*' => 'exists:jenis_order,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:7048'
        ]);

        $data = [
            'nama_jenis_spek_detail' => $request->nama_jenis_spek_detail,
            'id_jenis_spek' => $request->id_jenis_spek
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('jenis_spek_detail', 'public');
        }

        $detail = JenisSpekDetail::create($data);
        
        // Sync jenis_order (many-to-many)
        if ($request->id_jenis_order) {
            $detail->jenisOrder()->sync($request->id_jenis_order);
        }

        return back()->with('success', 'Jenis Spek Detail berhasil ditambahkan');
    }

    public function updateJenisSpekDetail(Request $request, $id)
    {
        $request->validate([
            'nama_jenis_spek_detail' => 'required|string|max:255',
            'id_jenis_spek' => 'required|exists:jenis_spek,id',
            'id_jenis_order' => 'nullable|array',
            'id_jenis_order.*' => 'exists:jenis_order,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:7048'
        ]);

        $detail = JenisSpekDetail::findOrFail($id);

        $detail->nama_jenis_spek_detail = $request->nama_jenis_spek_detail;
        $detail->id_jenis_spek = $request->id_jenis_spek;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                Storage::delete('public/' . $detail->gambar);
            }

            $detail->gambar = $request->file('gambar')->store('jenis_spek_detail', 'public');
        }

        $detail->save();
        
        // Sync jenis_order (many-to-many)
        if ($request->id_jenis_order) {
            $detail->jenisOrder()->sync($request->id_jenis_order);
        } else {
            $detail->jenisOrder()->detach();
        }

        return back()->with('success', 'Jenis Spek Detail berhasil diupdate');
    }

    public function destroyJenisSpekDetail($id)
    {
        $detail = JenisSpekDetail::findOrFail($id);

        // Hapus gambar jika ada
        if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
            Storage::delete('public/' . $detail->gambar);
        }

        $detail->delete();

        return back()->with('success', 'Jenis Spek Detail berhasil dihapus');
    }
}