<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\KemampuanProduksi;
use App\Models\KategoriJenisOrder;

class PelangganController extends Controller
{
    public function index()
    {
        // Ambil pelanggan + speknya
        $pelanggan = Pelanggan::all();

        return view('dashboard.pelanggan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'id_affiliates' => 'nullable'
        ]);

        Pelanggan::create($request->all());

        // Deteksi AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan'
            ]);
        }

        // Untuk non-AJAX, redirect dengan session
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelangganName = $pelanggan->nama;
            $pelanggan->delete();

            // Deteksi AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan "' . $pelangganName . '" berhasil dihapus'
                ]); 
            }

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan "' . $pelangganName . '" berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id) 
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'id_affiliates' => 'nullable'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        // Deteksi AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil diperbarui'
            ]);
        }

        // Untuk non-AJAX, redirect dengan session
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui');
    }
    
    public function show($id)
    {
        $pelanggan = Pelanggan::with([
            'orders.size',
            'orders.spesifikasi.jenisSpek',
            'orders.spesifikasi.jenisSpekDetail'
        ])->findOrFail($id);

        $pelanggans = Pelanggan::orderBy('nama', 'asc')->get();

        $totalHariSemuaOrder = $pelanggan->orders->sum('hari');

        $tanggalSelesai = null;
        if ($pelanggan->orders->count() > 0) {
            // Ambil tanggal order pertama atau tanggal sekarang
            $tanggalAwal = $pelanggan->orders->first()->created_at ?? now();
            
            // Total hari = hari produksi + hari packing
            $totalHari = $totalHariSemuaOrder;
            
            $tanggalSelesai = Carbon::parse($tanggalAwal)->addDays($totalHari);
        }

        $groupedSpecsByOrder = [];

        foreach ($pelanggan->orders as $order) {

            /* ===============================
            |  TANGGAL MASUK & SELESAI
            =============================== */

            // Tanggal masuk = created_at order
            $order->tgl_masuk = $order->created_at
                ? Carbon::parse($order->created_at)
                : null;

            // Tanggal selesai = created_at + hari (desimal)
            $order->tgl_selesai = null;
            if ($order->created_at && $order->hari) {
                $jam = round($order->hari * 24);
                $order->tgl_selesai = Carbon::parse($order->created_at)
                    ->addHours($jam)
                    ->addDays(2);
            }

            /* ===============================
            |  GROUP SPESIFIKASI PER ORDER
            =============================== */

            $grouped = $order->spesifikasi->groupBy('jenis_spek_id');

            $groupedSpecsByOrder[$order->id] = $grouped->map(function ($items) {
                return [
                    'spek_name' => optional($items->first()->jenisSpek)->nama_jenis_spek ?? 'Unknown',
                    'details' => $items->map(function ($item) {
                        return [
                            'name'  => optional($item->jenisSpekDetail)->nama_jenis_spek_detail ?? '-',
                            'image'=> optional($item->jenisSpekDetail)->gambar,
                            'id'    => $item->jenis_spek_detail_id
                        ];
                    })->toArray()
                ];
            });
        }

        return view('dashboard.pelanggan-detail', compact(
            'pelanggan',
            'pelanggans',
            'order',
            'groupedSpecsByOrder',
            'totalHariSemuaOrder',     // Total hari semua order
            'tanggalSelesai'
        ));
    }
    public function showNotaByPelanggan($id)
    {
        $pelanggan = Pelanggan::with([
            'affiliate',
            'orders.size',
            'orders.jenisOrder',
            'orders.jenisOrder.kategori',
            'orders.spesifikasi.jenisSpek',
            'orders.spesifikasi.jenisSpekDetail'
        ])->findOrFail($id);

        $pelanggans = Pelanggan::orderBy('nama', 'asc')->get();

        // ================================
        // FILTER ORDER: Pisahkan LUNAS dan BELUM LUNAS
        // ================================
        
        $ordersBelumLunas = $pelanggan->orders->filter(function ($order) {
            $pembayaranOrder = \App\Models\Pembayaran::where('order_id', $order->id)->first();
            return !$pembayaranOrder || !$pembayaranOrder->status;
        });

        $ordersSudahLunas = $pelanggan->orders->filter(function ($order) {
            $pembayaranOrder = \App\Models\Pembayaran::where('order_id', $order->id)->first();
            return $pembayaranOrder && $pembayaranOrder->status;
        });

        $adaOrderBelumLunas = $ordersBelumLunas->isNotEmpty();
        $adaOrderSudahLunas = $ordersSudahLunas->isNotEmpty();

        // ================================
        // PERHITUNGAN UNTUK BELUM LUNAS
        // ================================
        
        $pembayaranListBelumLunas = \App\Models\Pembayaran::where('pelanggan_id', $id)
            ->whereIn('order_id', $ordersBelumLunas->pluck('id')->toArray())
            ->get();
        
        // Ambil SEMUA data pembayaran untuk ditampilkan di tabel
        $pembayaranList = \App\Models\Pembayaran::where('pelanggan_id', $id)->get();
        
        // Hitung HANYA untuk order yang belum lunas
        $totalDPBelumLunas = $pembayaranListBelumLunas->sum('dp');
        $totalSisaBayarBelumLunas = $pembayaranListBelumLunas->sum('sisa_bayar');
        $totalHarusDibayarBelumLunas = $pembayaranListBelumLunas->sum('harus_dibayar');
        $totalSudahDibayarBelumLunas = $totalDPBelumLunas;
        
        // ================================
        // PERHITUNGAN UNTUK SEMUA ORDER
        // ================================
        
        // Hitung total untuk SEMUA order (lunas + belum lunas)
        $totalDPAll = $pembayaranList->sum('dp');
        $totalSisaBayarAll = $pembayaranList->sum('sisa_bayar');
        $totalHarusDibayarAll = $pembayaranList->sum('harus_dibayar');
        $totalSudahDibayarAll = $totalDPAll;
        
        // Status: LUNAS jika tidak ada order yang belum lunas
        $statusPembayaranKeseluruhan = !$adaOrderBelumLunas;
        
        $statusPembayaranText = $statusPembayaranKeseluruhan ? 'LUNAS' : 'BELUM LUNAS';
        $statusPembayaranClass = $statusPembayaranKeseluruhan ? 'text-green-600 font-bold' : 'text-red-600 font-bold';

        // ================================
        // AMBIL SEMUA NAMA_JOB YANG UNIK
        // ================================
        if ($adaOrderBelumLunas) {
            $semuaNamaJob = $ordersBelumLunas
                ->pluck('nama_job')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        } else {
            $semuaNamaJob = $pelanggan->orders
                ->pluck('nama_job')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        $groupedSpecsByOrder = [];

        $selectedKategoriIds = $pelanggan->orders
            ->pluck('jenisOrder.id_kategori_jenis_order')
            ->filter()
            ->unique()
            ->values();

        $allKategori = KategoriJenisOrder::orderBy('nama')->get();

        // ================================
        // GROUP ORDERS UNTUK DITAMPILKAN
        // ================================
        $ordersToDisplay = $adaOrderBelumLunas ? $ordersBelumLunas : $pelanggan->orders;
        $groupedOrders = $ordersToDisplay->groupBy('jenis_order_id');

        // Hitung total harga untuk yang ditampilkan
        $totalKeseluruhanHarga = $ordersToDisplay->sum(function ($order) {
            return ($order->qty ?? 0) * (optional($order->jenisOrder)->harga_jual ?? 0);
        });

        // Hitung total harga untuk SEMUA order
        $totalKeseluruhanHargaAll = $pelanggan->orders->sum(function ($order) {
            return ($order->qty ?? 0) * (optional($order->jenisOrder)->harga_jual ?? 0);
        });

        // Loop untuk semua order yang akan ditampilkan
        foreach ($ordersToDisplay as $order) {
            $order->tgl_masuk = $order->created_at
                ? Carbon::parse($order->created_at)
                : null;

            $order->tgl_selesai = null;
            if ($order->created_at && $order->hari) {
                $jam = round($order->hari * 24);
                $order->tgl_selesai = Carbon::parse($order->created_at)->addHours($jam);
            }

            $groupedSpecsByOrder[$order->id] = $order->spesifikasi
                ->groupBy('jenis_spek_id')
                ->map(function ($items) {
                    if ($items->isEmpty()) {
                        return null;
                    }

                    return [
                        'spek_name' => optional($items->first()->jenisSpek)->nama_jenis_spek ?? '-',
                        'details' => $items->map(function ($item) {
                            return [
                                'name'  => optional($item->jenisSpekDetail)->nama_jenis_spek_detail ?? '-',
                                'image' => optional($item->jenisSpekDetail)->gambar,
                                'id'    => $item->jenis_spek_detail_id
                            ];
                        })->toArray()
                    ];
                })
                ->filter()
                ->values();
        }

        // Additional variables for counts
        $totalOrderCount = $pelanggan->orders->count();
        $orderLunasCount = $ordersSudahLunas->count();
        $orderBelumLunasCount = $ordersBelumLunas->count();

        return view('dashboard.order-detail', compact(
            'pelanggan',
            'pelanggans',
            'groupedSpecsByOrder',
            'allKategori',
            'selectedKategoriIds',
            'groupedOrders',
            'totalKeseluruhanHarga',
            'totalKeseluruhanHargaAll',
            // Data pembayaran
            'pembayaranList',
            // Untuk order belum lunas
            'totalDPBelumLunas',
            'totalSisaBayarBelumLunas',
            'totalHarusDibayarBelumLunas',
            'totalSudahDibayarBelumLunas',
            // Untuk semua order
            'totalDPAll',
            'totalSisaBayarAll',
            'totalHarusDibayarAll',
            'totalSudahDibayarAll',
            // Status
            'statusPembayaranKeseluruhan',
            'statusPembayaranText',
            'statusPembayaranClass',
            // Nama job
            'semuaNamaJob',
            // Flag
            'adaOrderBelumLunas',
            'adaOrderSudahLunas',
            // Data untuk info
            'ordersBelumLunas',
            'ordersSudahLunas',
            // Count variables
            'totalOrderCount',
            'orderLunasCount',
            'orderBelumLunasCount'
        ));
    }

}
