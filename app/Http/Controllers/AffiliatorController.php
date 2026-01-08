<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\HargaAffiliator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AffiliatorController extends Controller
{
    // Menampilkan daftar afiliator
    public function index()
    {
        $affiliates = Affiliate::latest()
                        ->withCount('ordersViaKode') 
                        ->get();
        
        $kode = $this->generateUniqueCode();

        return view('dashboard.affiliator', compact('affiliates', 'kode'));
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

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:affiliates,kode',
            'kode_manual' => 'nullable|max:20|regex:/^[a-zA-Z0-9\-]+$/', // Validasi kode manual
            'nomor_whatsapp' => 'nullable',
            'nama_bank' => 'nullable',
            'nomor_rekening' => 'nullable',
            'nama_rekening' => 'nullable',
            'alamat' => 'nullable',
        ], [
            'kode_manual.regex' => 'Kode manual hanya boleh berisi huruf, angka, dan tanda hubung (-)',
            'kode_manual.max' => 'Kode manual maksimal 20 karakter',
        ]);

        // Format kode manual sebelum disimpan
        $kodeManual = $request->kode_manual ? trim($request->kode_manual) : '';
        
        // Kode sistem (5 karakter pertama dari kode yang dikirim)
        // Atau ambil dari generateUniqueCode() jika ada
        $kodeSystem = $this->generateUniqueCode();
        
        // Format akhir: KODE_MANUAL - KODE_SISTEM
        $kodeFinal = $kodeSystem;
        
        if ($kodeManual) {
            // Bersihkan kode manual
            $kodeManual = preg_replace('/[^a-zA-Z0-9\-]/', '', $kodeManual);
            $kodeManual = strtoupper($kodeManual);
            
            // Pastikan tidak ada tanda hubung berlebihan
            $kodeManual = trim($kodeManual, '-');
            $kodeManual = preg_replace('/-+/', '-', $kodeManual);
            
            $kodeFinal = $kodeManual . '-' . $kodeSystem;
        }

        // Cek lagi uniqueness untuk memastikan
        $exists = Affiliate::where('kode', $kodeFinal)->exists();
        if ($exists) {
            // Jika sudah ada, tambahkan angka di belakang kode manual
            $counter = 1;
            do {
                $newKodeManual = $kodeManual . $counter;
                $kodeFinal = $newKodeManual . '-' . $kodeSystem;
                $counter++;
            } while (Affiliate::where('kode', $kodeFinal)->exists());
        }

        // Simpan data
        Affiliate::create([
            'nama' => $request->nama,
            'kode' => $kodeFinal,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_rekening' => $request->nama_rekening,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Afiliator berhasil ditambahkan.');
    }

    // Mengupdate data
    public function update(Request $request, $id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->update($request->all());

        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:affiliates,kode,'.$affiliate->id, 
            'nomor_whatsapp' => 'nullable',
            'nama_bank' => 'nullable',
            'nomor_rekening' => 'nullable',
            'nama_rekening' => 'nullable',
            'alamat' => 'nullable',
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Affiliator berhasil diperbarui'
            ]);
        }

        // Untuk non-AJAX, redirect dengan session
        return redirect()->back()->with('success', 'Afiliator berhasil di edit.');
    }

    // Menghapus data
    public function destroy($id) 
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliateName = $affiliate->nama;
            $affiliate->delete(); 

            // Deteksi AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Affiliator "' . $affiliateName . '" berhasil dihapus'
                ]); 
            }

            return redirect()->route('affiliate.index')
                ->with('success', 'Affiliator "' . $affiliateName . '" berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus affiliate: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus affiliate: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // 1. Ambil data afiliator
            $affiliate = Affiliate::findOrFail($id);

            // 2. Ambil orders menggunakan relasi yang benar
            $orders = $affiliate->ordersViaKode()
                        ->with(['jenisOrder' => function($query) {
                            $query->select('id', 'nama_jenis', 'persentase_affiliate');
                        }])
                        ->latest()
                        ->get();

            // 3. Hitung total komisi dari kolom laba_bersih_affiliate
            $totalKomisi = $orders->sum('laba_bersih_affiliate');

            // 4. Hitung total qty semua order (pastikan field qty ada)
            $totalQtySemuaOrder = $orders->sum('qty') ?? 0;

            // 5. Filter order yang sudah selesai (status = 1)
            $completedOrders = $orders->filter(function ($order) {
                return (int) ($order->status ?? 0) === 1;
            });
            
            $completedCount = $completedOrders->count();
            $completedKomisi = $completedOrders->sum('laba_bersih_affiliate');

            // 6. Hitung total laba bersih untuk order selesai
            $completedLabaBersih = $completedOrders->sum(function($order) {
                return $order->laba_bersih ?? 0;
            });

            // 7. Debugging data
            \Log::info("Affiliate Detail - ID: {$id}", [
                'affiliate_name' => $affiliate->nama,
                'affiliate_kode' => $affiliate->kode,
                'total_orders' => $orders->count(),
                'total_qty' => $totalQtySemuaOrder,
                'total_komisi' => $totalKomisi,
                'completed_orders' => $completedCount,
                'completed_komisi' => $completedKomisi,
                'completed_laba_bersih' => $completedLabaBersih,
                'first_order_qty' => $orders->first() ? $orders->first()->qty : null
            ]);

            // 8. Pastikan semua variabel memiliki nilai default jika null
            $completedCount = $completedCount ?? 0;
            $completedKomisi = $completedKomisi ?? 0;
            $completedLabaBersih = $completedLabaBersih ?? 0;
            $totalKomisi = $totalKomisi ?? 0;
            $totalQtySemuaOrder = $totalQtySemuaOrder ?? 0;

            return view('dashboard.affiliator_detail', compact(
                'affiliate', 
                'orders', 
                'totalKomisi',
                'completedCount',
                'completedKomisi',
                'completedLabaBersih',
                'totalQtySemuaOrder'
            ));

        } catch (\Exception $e) {
            \Log::error("Error in AffiliateController show method: " . $e->getMessage());
            
            // Return dengan data kosong jika error
            return view('dashboard.affiliator_detail', [
                'affiliate' => Affiliate::find($id) ?? new Affiliate(),
                'orders' => collect(),
                'totalKomisi' => 0,
                'completedCount' => 0,
                'completedKomisi' => 0,
                'completedLabaBersih' => 0,
                'totalQtySemuaOrder' => 0
            ]);
        }
    }

    public function showLogin()
    {
        // Jika sudah login, redirect ke halaman detail
        if (Session::has('affiliate_id')) {
            return redirect()->route('affiliate.detail', Session::get('affiliate_id'));
        }
        
        return view('afiliasi');
    }

    /**
     * Proses login dengan kode
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50'
        ]);

        // Cari affiliate berdasarkan kode
        $affiliate = Affiliate::where('kode', $request->kode)->first();

        if (!$affiliate) {
            return back()->withErrors(['kode' => 'Kode affiliate tidak valid'])->withInput();
        }

        // Simpan session affiliate
        Session::put('affiliate_id', $affiliate->id);
        Session::put('affiliate_kode', $affiliate->kode);
        Session::put('affiliate_nama', $affiliate->nama);

        // Redirect ke halaman detail
        return redirect()->route('afiliasi-detail', $affiliate->id);
    }

    /**
     * Tampilkan halaman detail affiliate
     */
    public function showDetail($id)
    {
        // Middleware akan menangani autentikasi, tapi kita cek lagi
        if (!Session::has('affiliate_id') || Session::get('affiliate_id') != $id) {
            return redirect()->route('affiliate.login')->withErrors(['auth' => 'Silakan login terlebih dahulu']);
        }

        $affiliate = Affiliate::findOrFail($id);
        
        // Ambil data pelanggan yang terhubung dengan affiliate ini
        $pelanggans = $affiliate->pelanggans()->with(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        $orders = $affiliate->ordersViaKode()->with('jenisOrder')->latest()->get();

        // Ambil harga affiliator dari DB
        $hargaAffiliator = HargaAffiliator::first();
        $harga = $hargaAffiliator ? $hargaAffiliator->harga : 0;

        // Hitung total omset: jumlah order * harga per order
        $totalOmset = $orders->count() * $harga;

        // Hitung omset yang dapat dicairkan: hanya order dengan status == 1
        $completedOrders = $orders->filter(function ($o) {
            return (int) ($o->status ?? 1) === 1;
        });
        $completedCount = $completedOrders->count();
        $completedOmset = $completedCount * $harga;

        return view('afiliasi-detail', compact('affiliate', 'orders', 'harga', 'totalOmset', 'completedCount', 'completedOmset'));
    }

    /**
     * Logout affiliate
     */
    public function logout(Request $request)
    {
        Session::forget('affiliate_id');
        Session::forget('affiliate_kode');
        Session::forget('affiliate_nama');

        return redirect()->route('affiliate.login')->with('success', 'Anda telah logout');
    }
}