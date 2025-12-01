<?php

namespace App\Http\Controllers;

use App\Models\JenisBahan;
use Illuminate\Http\Request;

class JenisBahanController extends Controller
{
    public function index()
    {
        $jenisBahan = JenisBahan::all();

        // Tambahkan URL gambar agar mudah dipakai di Blade
        foreach ($jenisBahan as $item) {
            $item->gambar_url = asset('storage/' . $item->gambar);
        }

        return view('dashboard.spesifikasi.jenis_bahan.index', compact('jenisBahan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Jika ada upload gambar
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('jenis_bahan', 'public');
        }

        JenisBahan::create($validated);

        return back()->with('success', 'Jenis bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        JenisBahan::destroy($id);
        return back()->with('success', 'Jenis bahan dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $bahan = JenisBahan::findOrFail($id);

        // Jika gambar diganti
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($bahan->gambar && Storage::exists($bahan->gambar)) {
                Storage::delete($bahan->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('bahan', 'public');
            $bahan->gambar = $path;
        }

        $bahan->nama = $request->nama;
        $bahan->save();

        return back()->with('success', 'Jenis bahan berhasil diupdate');
    }
}
