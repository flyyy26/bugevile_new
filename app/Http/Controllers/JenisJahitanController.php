<?php

namespace App\Http\Controllers;

use App\Models\JenisJahitan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JenisJahitanController extends Controller
{
    public function index()
    {
        $jenisJahitan = JenisJahitan::all();

        // Tambahkan URL gambar agar mudah dipakai di Blade
        foreach ($jenisJahitan as $item) {
            $item->gambar_url = asset('storage/' . $item->gambar);
        }

        return view('dashboard.spesifikasi.jenis_bahan.index', compact('jenisJahitan'));
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

        JenisJahitan::create($validated);

        return back()->with('success', 'Jenis bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        JenisJahitan::destroy($id);
        return back()->with('success', 'Jenis jahitan dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $jahitan = JenisJahitan::findOrFail($id);

        // Jika gambar diganti
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($jahitan->gambar && Storage::exists($jahitan->gambar)) {
                Storage::delete($jahitan->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('jahitan', 'public');
            $jahitan->gambar = $path;
        }

        $jahitan->nama = $request->nama;
        $jahitan->save();

        return back()->with('success', 'Jenis jahitan berhasil diupdate');
    }

}
