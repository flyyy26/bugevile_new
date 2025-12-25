<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTotal;
use App\Models\JenisOrder;
use App\Models\KategoriJenisOrder;
use App\Models\Job;
use App\Models\JenisBahan;
use App\Models\JenisPola;
use App\Models\JenisKerah;
use App\Models\JenisJahitan;
use App\Models\JenisSpek;
use App\Models\JenisSpekDetail;
use App\Models\Size;
use App\Models\OrderSpesifikasi;
use App\Models\Pelanggan;
use App\Models\Affiliate;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();
        $orders = Order::with('affiliates')->latest()->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueJobs = Order::distinct('nama_job')->pluck('nama_job')->sort();
        $totals = OrderTotal::first();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        $pelanggans = Pelanggan::with(['affiliate' => function($query) {
        $query->select('id', 'kode', 'nama'); // Hanya ambil kolom yang diperlukan
        }])->get();

        $jenisBahan = JenisBahan::all();
        $jenisPola = JenisPola::all();
        $jenisKerah = JenisKerah::all();
        $jenisJahitan = JenisJahitan::all();
        
        // Load jenis_spek dengan detail yang di-eager-load
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();
        
        // Load jenis_spek_detail dengan relasi jenisOrder untuk filter di frontend
        $jenisSpekDetail = JenisSpekDetail::with('jenisOrder')->get();

        return view('dashboard.orders', compact(
            'orders',
            'uniqueJobs',
            'uniqueKonsumens',
            'totals',
            'jenisOrders',
            'kategoriList',
            'jobs',
            'jenisBahan',
            'jenisPola',
            'jenisKerah',
            'jenisJahitan',
            'jenisSpek',
            'jenisSpekDetail',
            'pelanggans'
        ));
    }


   public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_job' => 'required',
            'nama_konsumen' => 'required',
            'keterangan' => 'nullable',
            'xs' => 'nullable|integer',
            's' => 'nullable|integer',
            'm' => 'nullable|integer',
            'l' => 'nullable|integer',
            'xl' => 'nullable|integer',
            '2xl' => 'nullable|integer',
            '3xl' => 'nullable|integer',
            '4xl' => 'nullable|integer',
            '5xl' => 'nullable|integer',
            '6xl' => 'nullable|integer',
            'jenis_order_id' => 'required|exists:jenis_order,id',
            'speks' => 'nullable|array',
            'speks.*' => 'nullable|integer|exists:jenis_spek_detail,id',
            'affiliator_kode' => 'nullable|string',
        ]);

        // **Cari ID affiliate dari kode yang diinput user**
        $affiliateId = null;
        $affiliatorKode = $validated['affiliator_kode'] ?? null;
        
        if ($affiliatorKode) {
            $affiliate = \App\Models\Affiliate::where('kode', $affiliatorKode)->first();
            if ($affiliate) {
                $affiliateId = $affiliate->id; // Ambil ID-nya, bukan kode
                \Log::info("Kode affiliate '{$affiliatorKode}' ditemukan dengan ID: {$affiliateId}");
            } else {
                \Log::warning("Kode affiliate '{$affiliatorKode}' tidak ditemukan di database");
                // Opsional: return error jika kode tidak valid
                // return back()->withErrors(['affiliator_kode' => 'Kode affiliate tidak ditemukan'])->withInput();
            }
        }

        // Handle pelanggan
        if (is_numeric($request->nama_konsumen)) {
            // **PELANGGAN DARI DROPDOWN (sudah ada di database)**
            $pelanggan = Pelanggan::find($request->nama_konsumen);
            
            // Jika user input kode affiliate, update pelanggan dengan ID affiliate
            if ($affiliateId && $pelanggan) {
                $pelanggan->id_affiliates = $affiliateId;
                $pelanggan->save();
                \Log::info("Updated pelanggan {$pelanggan->id} dengan id_affiliates: {$affiliateId}");
            }
            
            // Jika user TIDAK input kode affiliate, ambil dari yang sudah ada
            if (!$affiliatorKode && $pelanggan && $pelanggan->id_affiliates) {
                $affiliateId = $pelanggan->id_affiliates;
                $affiliate = \App\Models\Affiliate::find($affiliateId);
                $affiliatorKode = $affiliate ? $affiliate->kode : null;
                \Log::info("Menggunakan affiliate existing pelanggan: {$affiliatorKode}");
            }
        } else {
            // **PELANGGAN BARU (input manual)**
            // Cari dulu apakah pelanggan dengan nama ini sudah ada
            $pelanggan = Pelanggan::where('nama', $request->nama_konsumen)->first();
            
            if ($pelanggan) {
                // Pelanggan sudah ada, update affiliate-nya jika ada
                if ($affiliateId) {
                    $pelanggan->id_affiliates = $affiliateId;
                    $pelanggan->save();
                    \Log::info("Updated pelanggan existing {$pelanggan->id} dengan id_affiliates: {$affiliateId}");
                }
            } else {
                // Pelanggan benar-benar baru, buat dengan affiliate
                $pelanggan = Pelanggan::create([
                    'nama' => $request->nama_konsumen,
                    'id_affiliates' => $affiliateId // Simpan ID, bukan kode
                ]);
                \Log::info("Created pelanggan baru dengan id_affiliates: {$affiliateId}");
            }
        }

        // Hitung qty
        $qty =
            ($request->xs ?? 0) +
            ($request->s ?? 0) +
            ($request->m ?? 0) +
            ($request->l ?? 0) +
            ($request->xl ?? 0) +
            ($request->input('2xl') ?? 0) +
            ($request->input('3xl') ?? 0) +
            ($request->input('4xl') ?? 0) +
            ($request->input('5xl') ?? 0) +
            ($request->input('6xl') ?? 0);

        $hari = $qty / 30;
        $hari = round($hari, 1);
        $deadline = $hari;

        $jenisOrder = JenisOrder::find($validated['jenis_order_id']);
        if (!$jenisOrder) {
            return back()->withErrors(['jenis_order_id' => 'Jenis order tidak ditemukan'])->withInput();
        }

        $job = Job::firstOrCreate(
            ['nama_job' => $validated['nama_job']],
            ['nama_job' => $validated['nama_job']]
        );

        // Buat order
        $order = Order::create([
            'nama_job' => $validated['nama_job'],
            'nama_konsumen' => $pelanggan->nama,
            'keterangan' => $validated['keterangan'] ?? null,
            'qty' => $qty,
            'hari' => $hari,
            'est' => $hari,
            'deadline' => $deadline,
            'jenis_order_id' => $validated['jenis_order_id'],
            'setting' => false,
            'status' => false,
            'print' => 0,
            'press' => 0,
            'cutting' => 0,
            'jahit' => 0,
            'finishing' => 0,
            'packing' => 0,
            'sisa_print' => $qty,
            'sisa_press' => $qty,
            'sisa_cutting' => $qty,
            'sisa_jahit' => $qty,
            'sisa_finishing' => $qty,
            'sisa_packing' => $qty,
            'affiliator_kode' => $affiliatorKode, // Simpan kode, bukan ID
        ]);

        // Hubungkan affiliate jika ada
        if ($affiliateId) {
            $order->affiliates()->attach($affiliateId);
        }

        // Buat size
        Size::create([
            'order_id' => $order->id,
            'xs' => $request->xs ?? 0,
            's' => $request->s ?? 0,
            'm' => $request->m ?? 0,
            'l' => $request->l ?? 0,
            'xl' => $request->xl ?? 0,
            '2xl' => $request->input('2xl') ?? 0,
            '3xl' => $request->input('3xl') ?? 0,
            '4xl' => $request->input('4xl') ?? 0,
            '5xl' => $request->input('5xl') ?? 0,
            '6xl' => $request->input('6xl') ?? 0,
        ]);

        // Persist spesifikasi
        $speks = $request->input('speks', []);
        if (is_array($speks) && count($speks) > 0) {
            foreach ($speks as $jenis_spek_id => $jenis_spek_detail_id) {
                if (!$jenis_spek_detail_id) continue;
                OrderSpesifikasi::create([
                    'order_id' => $order->id,
                    'jenis_spek_id' => $jenis_spek_id,
                    'jenis_spek_detail_id' => $jenis_spek_detail_id,
                ]);
            }
        }

        // Hubungkan pelanggan dengan order
        DB::table('pelanggan_orders')->insert([
            'pelanggan_id' => $pelanggan->id,
            'order_id' => $order->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->updateTotals();

        return redirect()->back()->with('success', 'Order berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $totals = $this->updateTotals();

        return response()->json([
            'status' => 'success',
            'message' => 'Order berhasil dihapus!',
            'totals' => $totals
        ], 200); // <-- WAJIB 200
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input (hanya terima 0 atau 1, atau true/false)
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $order = Order::findOrFail($id);
        
        // Validasi: tidak bisa tandai lunas jika sisa_packing !== 0
        if ($request->status && $order->sisa_packing != 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa tandai lunas, sisa packing masih ada!'
            ], 400);
        }
        
        // Update status
        $order->update([
            'status' => $request->status
        ]);

        // Pesan feedback sesuai status
        $msg = $request->status ? 'Order ditandai Lunas/Aktif.' : 'Order ditandai Belum Lunas.';

        return response()->json([
            'success' => true,
            'message' => $msg,
            'status' => $order->status
        ], 200);
    }

    private function updateTotals()
    {
        // HITUNG TOTAL
        $totalSettingCompleted = Order::where('setting', true)->count();
        $totalSettingRemaining = Order::where('setting', false)->count();

        $totals = [
            'total_qty'              => (int) Order::sum('qty'),
            'total_hari'             => (float) Order::sum('hari'),
            'total_deadline'         => (float) Order::sum('deadline'),
            'total_setting'          => (int) $totalSettingCompleted,
            'total_sisa_setting'     => (int) $totalSettingRemaining,
            'total_print'            => (int) Order::sum('print'),
            'total_press'            => (int) Order::sum('press'),
            'total_cutting'          => (int) Order::sum('cutting'),
            'total_jahit'            => (int) Order::sum('jahit'),
            'total_finishing'        => (int) Order::sum('finishing'),
            'total_packing'          => (int) Order::sum('packing'),
            'total_sisa_print'       => (int) Order::sum('sisa_print'),
            'total_sisa_press'       => (int) Order::sum('sisa_press'),
            'total_sisa_cutting'     => (int) Order::sum('sisa_cutting'),
            'total_sisa_jahit'       => (int) Order::sum('sisa_jahit'),
            'total_sisa_finishing'   => (int) Order::sum('sisa_finishing'),
            'total_sisa_packing'     => (int) Order::sum('sisa_packing'),
        ];

        // SIMPAN KE TABEL order_totals (atau OrderTotal model)
        OrderTotal::updateOrCreate(['id' => 1], $totals);

        // KEMBALIKAN data (sebagai object agar mudah diakses di JS)
        return (object) $totals;
    }

    public function detail($slug)
    {
        $order = Order::with('spesifikasi.jenisSpek', 'spesifikasi.jenisSpekDetail', 'size')
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil semua order untuk dropdown, kecuali yang sedang dibuka
        $orders = Order::orderBy('nama_job')->get();

        $totals = OrderTotal::first();

        return view('dashboard.orders-detail', compact('order', 'orders', 'totals'));
    }


}