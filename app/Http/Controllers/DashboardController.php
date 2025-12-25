<?php

namespace App\Http\Controllers;

use App\Models\OrderTotal;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\JenisOrder;
use App\Models\Job;
use App\Models\KategoriJenisOrder;
use App\Models\JenisBahan;
use App\Models\JenisPola;
use App\Models\JenisKerah;
use App\Models\JenisJahitan;
use App\Models\Pelanggan;
use App\Models\JenisSpek;
use App\Models\JenisSpekDetail;

class DashboardController extends Controller
{
    // Halaman progress keseluruhan
    public function index()
    {
        // Ambil 50 order terbaru sesuai kondisi, eager load 'jenisOrder'
        $orders = Order::with('jenisOrder')
            ->orderBy('sisa_print', 'desc')
            ->orderBy('sisa_press', 'desc')
            ->orderBy('sisa_cutting', 'desc')
            ->orderBy('sisa_jahit', 'desc')
            ->orderBy('sisa_finishing', 'desc')
            ->orderBy('sisa_packing', 'desc')
            ->latest()
            ->get();

        // Ambil semua order untuk select dropdown, dengan relasi 'jenisOrder'
        $ordersSelect = Order::with('jenisOrder')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data pegawai dengan relasi latestHistory
        $pegawais = Pegawai::with('latestHistory')->orderBy('nama', 'asc')->get();

        // Ambil 20 histori order terbaru
        $allHistories = OrderHistory::select(
            'id', 'order_id', 'jenis_pekerjaan', 'qty', 'pegawai_id', 'nama_job_snapshot', 'keterangan', 'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->get();

        // Data untuk popup input pesanan
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->limit(100)->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        $pelanggans = Pelanggan::all();
        $jenisBahan = JenisBahan::all();

        // Load jenis_spek tanpa circular references
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();
        $jenisSpekDetail = JenisSpekDetail::with('jenisOrder')->get();

        // Buat koleksi order terkait histori untuk mapping jenisOrder.nama_jenis
        $historyOrderIds = $allHistories->pluck('order_id')->filter()->unique()->values()->all();
        $ordersFromHistories = Order::whereIn('id', $historyOrderIds)->with('jenisOrder')->get();
        $ordersForMap = $ordersSelect->merge($ordersFromHistories)->unique('id')->values();

        // Ambil total global dari tabel order_totals (satu record saja)
        $totals = \App\Models\OrderTotal::first();

        return view('dashboard.index', compact(
            'totals', 'orders', 'pegawais', 'allHistories', 'ordersSelect', 'jenisBahan',
            'jenisOrders', 'jobs', 'kategoriList', 'uniqueKonsumens',
            'pelanggans', 'jenisSpek', 'jenisSpekDetail', 'ordersForMap'
        ));
    }

    // App\Http\Controllers\DashboardController.php
    public function lihatProgress()
    {
        // Ambil 50 order terbaru sesuai kondisi, eager load 'jenisOrder'
        $orders = Order::with('jenisOrder')
            ->orderBy('sisa_print', 'desc')
            ->orderBy('sisa_press', 'desc')
            ->orderBy('sisa_cutting', 'desc')
            ->orderBy('sisa_jahit', 'desc')
            ->orderBy('sisa_finishing', 'desc')
            ->orderBy('sisa_packing', 'desc')
            ->latest()
            ->get();

        // Ambil semua order untuk select dropdown, dengan relasi 'jenisOrder'
        $ordersSelect = Order::with('jenisOrder')
            ->where('sisa_packing', '>', 0) // ← FILTER DI SINI
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data pegawai dengan relasi latestHistory
        $pegawais = Pegawai::with('latestHistory')->orderBy('nama', 'asc')->get();

        // Ambil 20 histori order terbaru
        $allHistories = OrderHistory::select(
            'id', 'order_id', 'jenis_pekerjaan', 'qty', 'pegawai_id', 'nama_job_snapshot', 'keterangan', 'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->get();

        // Data untuk popup input pesanan
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->limit(100)->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        $pelanggans = Pelanggan::all();
        $jenisBahan = JenisBahan::all();

        // Load jenis_spek tanpa circular references
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();
        $jenisSpekDetail = JenisSpekDetail::with('jenisOrder')->get();

        // Buat koleksi order terkait histori untuk mapping jenisOrder.nama_jenis
        $historyOrderIds = $allHistories->pluck('order_id')->filter()->unique()->values()->all();
        $ordersFromHistories = Order::whereIn('id', $historyOrderIds)->with('jenisOrder')->get();
        $ordersForMap = $ordersSelect->merge($ordersFromHistories)->unique('id')->values();

        // Ambil total global dari tabel order_totals (satu record saja)
        $totals = \App\Models\OrderTotal::first();

        return view('lihat-progres', compact(
            'totals', 'orders', 'pegawais', 'allHistories', 'ordersSelect', 'jenisBahan',
            'jenisOrders', 'jobs', 'kategoriList', 'uniqueKonsumens',
            'pelanggans', 'jenisSpek', 'jenisSpekDetail', 'ordersForMap'
        ));
    }

    // Halaman progress per job
    public function showBySlug($slug)
    {
        $job = Order::where('slug', $slug)
            ->with([
                'latestSettingHistory.pegawai',
                'latestPrintHistory.pegawai',
                'latestPressHistory.pegawai',
                'latestCuttingHistory.pegawai',
                'latestJahitHistory.pegawai',
                'latestFinishingHistory.pegawai',
                'latestPackingHistory.pegawai',
            ])
            ->firstOrFail();

        $orders = Order::orderBy('created_at', 'desc')->get();
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        $allHistories = OrderHistory::with('pegawai')->orderBy('created_at', 'desc')->get();

        // Data untuk popup input pesanan
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        $pelanggans = Pelanggan::all();

        $jenisBahan = JenisBahan::all();
        $jenisPola = JenisPola::all();
        $jenisKerah = JenisKerah::all();
        $jenisJahitan = JenisJahitan::all();
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();

        $jenisSpekDetail = JenisSpekDetail::with('jenisOrder')->get();

        return view('dashboard.job', compact(
            'pegawais',
            'job',
            'orders',
            'allHistories',
            'jenisOrders',
            'jobs',
            'kategoriList',
            'uniqueKonsumens',
            'jenisBahan',
            'jenisPola',
            'jenisKerah',
            'jenisJahitan',
            'pelanggans',
            'jenisSpek',
            'jenisSpekDetail'
        ));
    }

    public function showBySlugProgress($slug)
    {
        $job = Order::where('slug', $slug)
            ->with([
                'latestSettingHistory.pegawai',
                'latestPrintHistory.pegawai',
                'latestPressHistory.pegawai',
                'latestCuttingHistory.pegawai',
                'latestJahitHistory.pegawai',
                'latestFinishingHistory.pegawai',
                'latestPackingHistory.pegawai',
            ])
            ->firstOrFail();

        $orders = Order::orderBy('created_at', 'desc')->where('sisa_packing', '>', 0)->get();
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        $allHistories = OrderHistory::with('pegawai')->orderBy('created_at', 'desc')->get();

        // Data untuk popup input pesanan
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        $pelanggans = Pelanggan::all();

        $jenisBahan = JenisBahan::all();
        $jenisPola = JenisPola::all();
        $jenisKerah = JenisKerah::all();
        $jenisJahitan = JenisJahitan::all();
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();

        $jenisSpekDetail = JenisSpekDetail::with('jenisOrder')->get();

        return view('show-lihat-progres', compact(
            'pegawais',
            'job',
            'orders',
            'allHistories',
            'jenisOrders',
            'jobs',
            'kategoriList',
            'uniqueKonsumens',
            'jenisBahan',
            'jenisPola',
            'jenisKerah',
            'jenisJahitan',
            'pelanggans',
            'jenisSpek',
            'jenisSpekDetail'
        ));
    }


    private function customRound($number)
    {
        $result = round($number, 1);
        return $result;
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'job_id'  => 'required|exists:orders,id',
            'kategori' => 'required|in:setting,print,press,cutting,jahit,finishing,packing',
            'qty'      => 'required|numeric|min:1',
            'pegawai_id' => 'required|exists:pegawais,id',
            'keterangan' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->job_id);

        $pegawaiId = $request->pegawai_id;
        // 🔥 Ambil data total keseluruhan (1 data saja)
        $totals = OrderTotal::first();  

        $kategori = strtolower($request->kategori);

        switch ($kategori) {
            case 'setting':
                // VALIDASI — Jika setting sudah 1, tidak boleh diisi lagi
                if ($order->setting == 1) {
                    return back()->with('error', 'Setting sudah dikerjakan!');
                }

                // Update tabel orders (per job)
                $order->setting = 1; // dari 0 ke 1

                // Update tabel order_totals
                $totals->total_setting += 1;
                $totals->total_sisa_setting -= 1;

                break;
            
            case 'print':
                if ($order->sisa_print < $request->qty) {
                    return back()->with('error', 'Melebihi sisa Print!');
                }

                // Ambil nilai jenis order (tetap sama untuk print & press)
                $nilaiJenis = $order->jenisOrder?->nilai ?? 1;

                // Hitung total lembar print
                $totalLembarPrint = $request->qty * $nilaiJenis;

                // UPDATE orders (per job)
                $order->print += $request->qty;
                $order->sisa_print -= $request->qty;
                $order->total_lembar_print = ($order->total_lembar_print ?? 0) + $totalLembarPrint;

                // UPDATE order_totals
                $totals->total_print += $request->qty;
                $totals->total_sisa_print -= $request->qty;

                break;


            case 'press':

                if ($order->sisa_press < $request->qty) {
                    return back()->with('error', 'Melebihi sisa Press!');
                }

                // Ambil nilai jenis order (tetap sama untuk print & press)
                $nilaiJenis = $order->jenisOrder?->nilai ?? 1;

                // Hitung total lembar press
                $totalLembarPress = $request->qty * $nilaiJenis;

                // UPDATE orders (per job)
                $order->press += $request->qty;
                $order->sisa_press -= $request->qty;
                $order->total_lembar_press = ($order->total_lembar_press ?? 0) + $totalLembarPress;

                // UPDATE order_totals
                $totals->total_press += $request->qty;
                $totals->total_sisa_press -= $request->qty;

                break;

            case 'cutting':
                if ($order->sisa_cutting < $request->qty)
                    return back()->with('error', 'Melebihi sisa Cutting!');

                $order->cutting += $request->qty;
                $order->sisa_cutting -= $request->qty;

                $totals->total_cutting += $request->qty;
                $totals->total_sisa_cutting -= $request->qty;

                break;

            case 'jahit':
                if ($order->sisa_jahit < $request->qty)
                    return back()->with('error', 'Melebihi sisa Jahit!');

                $order->jahit += $request->qty;
                $order->sisa_jahit -= $request->qty;

                // Update total jahit
                $totals->total_jahit += $request->qty;
                $totals->total_sisa_jahit -= $request->qty;

                // Hitung hari sisa (float)
                $hari = $order->sisa_jahit / 30;

                // Gunakan fungsi pembulatan custom
                $order->deadline = $this->customRound($hari);

                break;

            case 'finishing':
                if ($order->sisa_finishing < $request->qty)
                    return back()->with('error', 'Melebihi sisa Finishing!');

                $order->finishing += $request->qty;
                $order->sisa_finishing -= $request->qty;

                $totals->total_finishing += $request->qty;
                $totals->total_sisa_finishing -= $request->qty;

                break;

            case 'packing':
                if ($order->sisa_packing < $request->qty)
                    return back()->with('error', 'Melebihi sisa Packing!');

                $order->packing += $request->qty;
                $order->sisa_packing -= $request->qty;

                $totals->total_packing += $request->qty;
                $totals->total_sisa_packing -= $request->qty;

                break;
        }

        
        // SIMPAN PERUBAHAN
        $order->save();
        $totalDeadline = Order::sum('deadline');
        $totals->total_deadline = $totalDeadline;
        $totals->save();

        // SIMPAN HISTORY
        OrderHistory::create([
            'order_id'           => $order->id,
            'pegawai_id'        => $pegawaiId,
            'jenis_pekerjaan'    => ucfirst($kategori),
            'qty'                => $request->qty,
            'keterangan'         => $request->keterangan,
            'nama_job_snapshot'  => $order->nama_job,
        ]);

        return back()->with('success', 'Progress berhasil disimpan!');
    }


}
