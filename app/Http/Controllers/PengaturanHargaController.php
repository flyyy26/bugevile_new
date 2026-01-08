<?php
namespace App\Http\Controllers;

use App\Models\HargaJenisPekerjaan;
use App\Models\KategoriJenisOrder;
use App\Models\JenisOrder;
use App\Models\Job;
use App\Models\Affiliate;
use App\Models\HargaAffiliator;
use Illuminate\Http\Request;
use App\Models\KemampuanProduksi;

class PengaturanHargaController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::latest()
                        ->withCount('ordersViaKode') 
                        ->get();
        
        $print = KemampuanProduksi::where('nama_kemampuan', 'Print')->first();
        $packingFinishing = KemampuanProduksi::where('nama_kemampuan', 'Packing & Finishing')->first();
        
        $kode = $this->generateUniqueCode();

        $kategori = KategoriJenisOrder::with('jenisSpek.detail')->get();
        
        // Ambil semua jenis_order dengan relasi kategorinya untuk ditampilkan di modal
        $jenisOrderList = \App\Models\JenisOrder::all();
        // Ambil harga ID 1
        $kategoriList = KategoriJenisOrder::all();
        $jenisOrders = JenisOrder::with(['hargaJenisPekerjaan'])->get();
        $job = Job::all();
        $harga = HargaJenisPekerjaan::find(1);
        $komisi = HargaAffiliator::firstOrNew([], ['harga' => 0]);
        
        // Jika belum ada di DB, simpan dulu biar punya ID untuk route update
        if (!$komisi->exists) {
            $komisi->save();
        }

        $persentaseKomisi = $komisi->harga ?? 0;

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

        return view('dashboard.pengaturan', compact('print', 'packingFinishing', 'harga', 'kategori', 'jenisOrderList', 'kategoriList', 'jenisOrders', 'job', 'komisi','persentaseKomisi', 'affiliates', 'kode' ));
    }
    private function generateUniqueCode()
    {
        do {
            // 1. Ambil 2 Huruf Acak Kapital
            $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);
            
            // 2. Ambil 3 Angka Acak
            $numbers = substr(str_shuffle('0123456789'), 0, 3);

            // 3. Gabungkan dan Acak Posisi (agar tidak selalu huruf duluan)
            $code = str_shuffle($letters . $numbers);
            
            // 4. Cek di database apakah kode sudah ada
            // Loop akan terus berjalan jika exists() mengembalikan true
        } while (Affiliate::where('kode', 'LIKE', $code . '%')->exists());

        return $code;
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
