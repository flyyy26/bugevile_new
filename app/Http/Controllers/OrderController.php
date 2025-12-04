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

class OrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();
        $orders = Order::latest()->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueJobs = Order::distinct('nama_job')->pluck('nama_job')->sort();
        $totals = OrderTotal::first();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();

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
            'jenisSpekDetail'
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
            // removed unused spesifikasi fields: id_jenis_bahan, id_jenis_pola, id_jenis_kerah, id_jenis_jahitan
        ]);

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

        if (! $jenisOrder) {
            return back()->withErrors(['jenis_order_id' => 'Jenis order tidak ditemukan'])->withInput();
        }

        // Nama job tetap, tidak digabung
        $namaJobFinal = $validated['nama_job'];

        $job = Job::firstOrCreate(
            ['nama_job' => $namaJobFinal],
            ['nama_job' => $namaJobFinal]
        );

        $order = Order::create([
            'nama_job' => $namaJobFinal,
            'nama_konsumen' => $validated['nama_konsumen'],
            'keterangan' => $validated['keterangan'],
            'qty' => $qty,
            'hari' => $hari,
            'est' => $hari,
            'deadline' => $deadline,
            'jenis_order_id' => $validated['jenis_order_id'],
            'setting' => false,
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
        ]);

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

        // Persist selected spesifikasi (speks) if provided
        $speks = $request->input('speks', []);
        if (is_array($speks) && count($speks) > 0) {
            foreach ($speks as $jenis_spek_id => $jenis_spek_detail_id) {
                if (! $jenis_spek_detail_id) continue;
                OrderSpesifikasi::create([
                    'order_id' => $order->id,
                    'jenis_spek_id' => $jenis_spek_id,
                    'jenis_spek_detail_id' => $jenis_spek_detail_id,
                ]);
            }
        }

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
        $order = Order::with('spesifikasi.jenisSpek', 'spesifikasi.jenisSpekDetail')
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil semua order untuk dropdown, kecuali yang sedang dibuka
        $orders = Order::orderBy('nama_job')->get();

        $totals = OrderTotal::first();

        return view('dashboard.orders-detail', compact('order', 'orders', 'totals'));
    }


}