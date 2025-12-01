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
        KategoriJenisOrder::findOrFail($id)->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
