<?php

namespace App\Http\Controllers;

use App\Models\JenisKerah;
use Illuminate\Http\Request;

class JenisKerahController extends Controller
{
    public function index()
    {
        $jenisKerah = JenisKerah::all();

        // Tambahkan URL gambar agar mudah dipakai di Blade
        foreach ($jenisKerah as $item) {
            $item->gambar_url = asset('storage/' . $item->gambar);
        }

        return view('dashboard.spesifikasi.jenis_bahan.index', compact('jenisKerah'));
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

        JenisKerah::create($validated);

        return back()->with('success', 'Jenis bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        JenisKerah::destroy($id);
        return back()->with('success', 'Jenis kerah dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $kerah = JenisKerah::findOrFail($id);

        // Jika gambar diganti
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($kerah->gambar && Storage::exists($kerah->gambar)) {
                Storage::delete($kerah->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('kerah', 'public');
            $kerah->gambar = $path;
        }

        $kerah->nama = $request->nama;
        $kerah->save();

        return back()->with('success', 'Jenis kerah berhasil diupdate');
    }
}
