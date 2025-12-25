<?php
namespace App\Http\Controllers;

use App\Models\HargaJenisPekerjaan;
use App\Models\KategoriJenisOrder;
use App\Models\JenisOrder;
use App\Models\Job;
use App\Models\HargaAffiliator;
use Illuminate\Http\Request;

class PengaturanHargaController extends Controller
{
    public function index()
    {
        // Ambil harga ID 1
        $kategoriList = KategoriJenisOrder::all();
        $jenisOrders = JenisOrder::all();
        $job = Job::all();
        $harga = HargaJenisPekerjaan::find(1);
        $komisi = HargaAffiliator::firstOrNew([], ['harga' => 0]);
        
        // Jika belum ada di DB, simpan dulu biar punya ID untuk route update
        if (!$komisi->exists) {
            $komisi->save();
        }

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

        return view('dashboard.pengaturan', compact('harga', 'kategoriList', 'jenisOrders', 'job', 'komisi'));
    }

    public function update(Request $request) // Hapus , $field
    {
        // 1. Validasi semua field yang dikirim dari form
        $validated = $request->validate([
            'harga_setting'   => 'required|numeric|min:0',
            'harga_print'     => 'required|numeric|min:0',
            'harga_press'     => 'required|numeric|min:0',
            'harga_cutting'   => 'required|numeric|min:0',
            'harga_jahit'     => 'required|numeric|min:0',
            'harga_finishing' => 'required|numeric|min:0',
            'harga_packing'   => 'required|numeric|min:0',
        ]);

        $harga = HargaJenisPekerjaan::find(1);

        if (!$harga) {
            return back()->with('error', 'Data harga tidak ditemukan.');
        }

        // 2. Update sekaligus menggunakan data yang sudah divalidasi
        $harga->update($validated);

        return back()->with('success', 'Semua harga berhasil diperbarui.');
    }

    // Job CRUD handlers
    public function storeJob(Request $request)
    {
        $request->validate([
            'nama_job' => 'required|string|max:255'
        ]);

        $job = new Job();
        $job->nama_job = $request->nama_job;
        $job->save();

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function updateJob(Request $request, $id)
    {
        $request->validate([
            'nama_job' => 'required|string|max:255'
        ]);

        $job = Job::find($id);
        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job tidak ditemukan'], 404);
        }

        $job->nama_job = $request->nama_job;
        $job->save();

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function destroyJob($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return back()->with('error', 'Job tidak ditemukan.');
        }

        $job->delete();
        return back()->with('success', 'Job berhasil dihapus.');
    }
}
