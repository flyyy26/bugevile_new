<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        // Ambil pelanggan + speknya
        $pelanggan = Pelanggan::all();

        return view('dashboard.pelanggan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'id_affiliates' => 'nullable'
        ]);

        Pelanggan::create($request->all());

        // Deteksi AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan'
            ]);
        }

        // Untuk non-AJAX, redirect dengan session
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelangganName = $pelanggan->nama;
            $pelanggan->delete();

            // Deteksi AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan "' . $pelangganName . '" berhasil dihapus'
                ]);
            }

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan "' . $pelangganName . '" berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'id_affiliates' => 'nullable'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        // Deteksi AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil diperbarui'
            ]);
        }

        // Untuk non-AJAX, redirect dengan session
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui');
    }
    
    public function show($id)
    {
        $pelanggan = Pelanggan::with([
            'orders.size',
            'orders.spesifikasi.jenisSpek',
            'orders.spesifikasi.jenisSpekDetail'
        ])->findOrFail($id);

        $pelanggans = Pelanggan::orderBy('nama','asc')->get();

        return view('dashboard.pelanggan-detail', compact('pelanggan', 'pelanggans'));
    }
}
