<?php
namespace App\Http\Controllers;

use App\Models\HargaJenisPekerjaan;
use App\Models\KategoriJenisOrder;
use App\Models\JenisOrder;
use Illuminate\Http\Request;

class PengaturanHargaController extends Controller
{
    public function index()
    {
        // Ambil harga ID 1
        $kategoriList = KategoriJenisOrder::all();
        $jenisOrders = JenisOrder::all();
        $harga = HargaJenisPekerjaan::find(1);

        // Jika belum ada, buat default
        if (!$harga) {
            $harga = HargaJenisPekerjaan::create([
                'harga_setting'   => 0,
                'harga_print'     => 0,
                'harga_press'     => 0,
                'harga_cutting'   => 0,
                'harga_jahit'     => 0,
                'harga_finishing' => 0,
                'harga_packing'   => 0,
            ]);
        }

        return view('dashboard.pengaturan', compact('harga', 'kategoriList', 'jenisOrders'));
    }

    public function update(Request $request, $field)
    {
        $request->validate([
            'value' => 'required|numeric|min:0',
        ]);

        $harga = HargaJenisPekerjaan::find(1);

        if (!$harga) {
            return back()->with('error', 'Data harga tidak ditemukan.');
        }

        // Pastikan field valid
        $validFields = [
            'harga_setting', 'harga_print', 'harga_press', 'harga_cutting',
            'harga_jahit', 'harga_finishing', 'harga_packing'
        ];

        if (!in_array($field, $validFields)) {
            return back()->with('error', 'Field tidak valid.');
        }

        $harga->$field = $request->value;
        $harga->save();

        return back()->with('success', 'Harga berhasil diperbarui.');
    }
}
