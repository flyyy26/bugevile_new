<?php
namespace App\Http\Controllers;

use App\Models\HargaJenisPekerjaan;
use App\Models\KategoriJenisOrder;
use App\Models\JenisOrder;
use App\Models\Job;
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

        return view('dashboard.pengaturan', compact('harga', 'kategoriList', 'jenisOrders', 'job'));
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
