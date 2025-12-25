<?php

namespace App\Http\Controllers;

use App\Models\JenisOrder;
use Illuminate\Http\Request;
use App\Models\KategoriJenisOrder;

class JenisOrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::with('kategori')->get();
        $kategoriList = KategoriJenisOrder::all();

        return view('dashboard.orders.setting.index', compact('jenisOrders', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'id_kategori_jenis_order' => 'required|exists:kategori_jenis_order,id'
        ]);

        JenisOrder::create([
            'nama_jenis' => $request->nama_jenis,
            'nilai' => $request->nilai,
            'id_kategori_jenis_order' => $request->id_kategori_jenis_order,
        ]);

        return back()->with('success', 'Jenis Order berhasil ditambahkan');
    }

    public function destroy($id)
    {
        JenisOrder::findOrFail($id)->delete();
        return back()->with('success', 'Jenis Order berhasil dihapus');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required',
            'nilai' => 'required|numeric',
            'id_kategori_jenis_order' => 'required'
        ]);

        $jenis = JenisOrder::findOrFail($id);

        $jenis->update([
            'nama_jenis' => $request->nama_jenis,
            'nilai' => $request->nilai,
            'id_kategori_jenis_order' => $request->id_kategori_jenis_order
        ]);

        return response()->json(['success' => true]);
    }

}
