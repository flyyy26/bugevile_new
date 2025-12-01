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

class OrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();
        $orders = Order::latest()->get();
        $kategoriList = KategoriJenisOrder::all();
        $uniqueJobs = Order::distinct('nama_job')->pluck('nama_job')->sort();
        $totals = OrderTotal::first();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();

        $jenisBahan = JenisBahan::all();
        $jenisPola = JenisPola::all();
        $jenisKerah = JenisKerah::all();
        $jenisJahitan = JenisJahitan::all();

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
            'jenisJahitan'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_job' => 'required',
            'nama_konsumen' => 'required',
            'keterangan' => 'nullable',
            'qty'      => 'required|integer',
            'jenis_order_id' => 'required|exists:jenis_order,id',
            'id_jenis_bahan' => 'nullable|exists:jenis_bahan,id',
            'id_jenis_pola' => 'nullable|exists:jenis_pola,id',
            'id_jenis_kerah' => 'nullable|exists:jenis_kerah,id',
            'id_jenis_jahitan' => 'nullable|exists:jenis_jahitan,id',
        ]);

        $qty = $validated['qty'];

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

        Order::create([
            'nama_job' => $namaJobFinal,
            'nama_konsumen' => $validated['nama_konsumen'],
            'keterangan' => $validated['keterangan'],
            'qty' => $qty,
            'hari' => $hari,
            'est' => $hari,
            'deadline' => $deadline,
            'jenis_order_id' => $validated['jenis_order_id'],
            'id_jenis_bahan' => $validated['id_jenis_bahan'] ?? null,
            'id_jenis_pola' => $validated['id_jenis_pola'] ?? null,
            'id_jenis_kerah' => $validated['id_jenis_kerah'] ?? null,
            'id_jenis_jahitan' => $validated['id_jenis_jahitan'] ?? null,
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
        $order = Order::where('slug', $slug)->firstOrFail();

        // Ambil semua order untuk dropdown, kecuali yang sedang dibuka
        $orders = Order::orderBy('nama_job')->get();

        $totals = OrderTotal::first();

        return view('dashboard.orders-detail', compact('order', 'orders', 'totals'));
    }


}