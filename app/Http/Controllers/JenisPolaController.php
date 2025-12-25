<?php

namespace App\Http\Controllers;

use App\Models\JenisPola;
use Illuminate\Http\Request;

class JenisPolaController extends Controller
{
    public function index()
    {
        $jenisPola = JenisPola::all();

        // Tambahkan URL gambar agar mudah dipakai di Blade
        foreach ($jenisPola as $item) {
            $item->gambar_url = asset('storage/' . $item->gambar);
        }

        return view('dashboard.spesifikasi.jenis_bahan.index', compact('jenisPola'));
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

        JenisPola::create($validated);

        return back()->with('success', 'Jenis bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        JenisPola::destroy($id);
        return back()->with('success', 'Jenis pola dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $pola = JenisPola::findOrFail($id);

        // Jika gambar diganti
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($pola->gambar && Storage::exists($pola->gambar)) {
                Storage::delete($pola->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('pola', 'public');
            $pola->gambar = $path;
        }

        $pola->nama = $request->nama;
        $pola->save();

        return back()->with('success', 'Jenis pola berhasil diupdate');
    }
}
