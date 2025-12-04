<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\OrderHistory;
use App\Models\Casbon;
use App\Models\Order;
use App\Models\HargaJenisPekerjaan;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{

    public function index()
    {
        $hargaDb = HargaJenisPekerjaan::find(1);
        // Daftar harga per jenis pekerjaan
        if (!$hargaDb) {
            $hargaDb = \App\Models\HargaJenisPekerjaan::create([
                'harga_setting'   => 0,
                'harga_print'     => 0,
                'harga_press'     => 0,
                'harga_cutting'   => 0,
                'harga_jahit'     => 0,
                'harga_finishing' => 0,
                'harga_packing'   => 0,
            ]);
        }

        // Masukkan ke array $harga sesuai struktur sebelumnya
        $harga = [
            'Setting'   => $hargaDb->harga_setting,
            'Print'     => $hargaDb->harga_print,
            'Press'     => $hargaDb->harga_press,
            'Cutting'   => $hargaDb->harga_cutting,
            'Jahit'     => $hargaDb->harga_jahit,
            'Finishing' => $hargaDb->harga_finishing,
            'Packing'   => $hargaDb->harga_packing,
        ];

        // 1. Ambil semua pegawai dan eager load riwayat pekerjaan (order_histories) dan casbon
        // Sertakan juga relasi order di dalam histories supaya frontend bisa mengakses
        // nilai seperti total_lembar_print / total_lembar_press saat filtering per bulan.
        $pegawais = Pegawai::with(['casbons', 'histories.order'])->get();

        $urutanPosisi = ['Setting', 'Print', 'Press', 'Cutting', 'Jahit', 'Finishing', 'Packing'];

        $pegawaiByPosisi = $pegawais
            ->groupBy('posisi')
            ->sortBy(function($value, $key) use ($urutanPosisi) {
                return array_search($key, $urutanPosisi);
            });

        $orders = Order::with('jenisOrder')->orderBy('created_at', 'desc')->get();

        $orderJenisMap = $orders->mapWithKeys(function($order) {
            return [
                $order->id => $order->jenisOrder->nama_jenis ?? 'Tidak Ada'
            ];
        });
        $orderJenisMapNilai = $orders->mapWithKeys(function($orderNilai) {
            return [
                $orderNilai->id => $orderNilai->jenisOrder->nilai ?? 'Tidak Ada'
            ];
        });
        $orderKonsumenMap = Order::pluck('nama_konsumen', 'id')->toArray();
        
        $rekapPerPegawai = [];
        $allHistoriesForJs = []; // <-- Variabel untuk history mentah JS
        $grandTotalKeseluruhan = 0;

        foreach ($pegawais as $pegawai) {
            $totalKeseluruhanPegawai = 0;
            
            // Mengelompokkan riwayat berdasarkan jenis pekerjaan dan menghitung total QTY
            $historiesGrouped = $pegawai->histories
                ->groupBy('jenis_pekerjaan')
                ->map(function ($group) use ($harga, &$totalKeseluruhanPegawai) {
                    $totalQty = $group->sum('qty');

                    $jenis = $group->first()->jenis_pekerjaan;
                    $hargaSatuan = $harga[$jenis] ?? 0;
                    $total = $totalQty * $hargaSatuan;

                    $totalLembar = 0;

                    foreach ($group as $history) {
                        if ($jenis === 'Print') {
                            $totalLembar += $history->order->total_lembar_print ?? 0;
                        } elseif ($jenis === 'Press') {
                            $totalLembar += $history->order->total_lembar_press ?? 0;
                        }
                    }

                    if ($jenis === 'Print') {
                        $hargaSatuan = $harga[$jenis] ?? 0;
                        $total = $totalLembar * $hargaSatuan;
                    } elseif ($jenis === 'Press') {
                        // total lembar * harga Press 250
                        $total = $totalLembar * $hargaSatuan;
                    } else {
                        // jenis lain tetap: total Qty * harga satuan
                        $hargaSatuan = $harga[$jenis] ?? 0;
                        $total = $totalQty * $hargaSatuan;
                    }

                    $totalKeseluruhanPegawai += $total;

                    return (object) [
                        'jenis_pekerjaan' => $jenis,
                        'total_qty' => $totalQty,
                        'total_lembar' => $totalLembar,
                        'total' => $total,
                        'raw_histories' => $group->all(),
                    ];
                });

            // GABUNGKAN SEMUA HISTORY MENTAH KE DALAM ARRAY GLOBAL UNTUK FILTER FRONTEND
            $allHistoriesForJs = array_merge($allHistoriesForJs, $pegawai->histories->toArray());

            $rekapPerPegawai[$pegawai->id] = (object) [
                'rekapJenis' => $historiesGrouped->values(),
                'totalKeseluruhan' => $totalKeseluruhanPegawai,
                'totalCasbon' => $pegawai->casbons->sum('jumlah'),
                'totalSisa' => $totalKeseluruhanPegawai - $pegawai->casbons->sum('jumlah'),
                // Sertakan raw_histories di tingkat pegawai agar frontend dapat
                // langsung memfilter berdasarkan created_at / updated_at per pegawai.
                'raw_histories' => $pegawai->histories->map(function($h) {
                    // Pastikan menyertakan beberapa field order penting jika tersedia
                    $arr = $h->toArray();
                    if ($h->relationLoaded('order') && $h->order) {
                        $arr['order'] = $h->order->toArray();
                    }
                    return $arr;
                })->all(),
            ];

            $grandTotalKeseluruhan += $totalKeseluruhanPegawai;
        }

        // 3. Ambil daftar bulan yang tersedia (untuk filter frontend)
        $availableMonths = OrderHistory::selectRaw('DISTINCT MONTH(created_at) as month, YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('dashboard.pegawai', [
            'pegawais'            => $pegawais,
            'rekapPerPegawai'     => $rekapPerPegawai,
            'harga'               => $harga,
            'availableMonths'     => $availableMonths,
            'allHistoriesForJs'   => $allHistoriesForJs,
            'orders'              => $orders,
            'pegawaiByPosisi'     => $pegawaiByPosisi,
            'orderJenisMap'       => $orderJenisMap,
            'orderJenisMapNilai'  => $orderJenisMapNilai,
            'orderKonsumenMap'    => $orderKonsumenMap, 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'posisi' => 'nullable|string',
            'rekening' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        Pegawai::create($request->all());

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $pegawai->update([
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'rekening' => $request->rekening,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'success' => true,
            'pegawai' => $pegawai
        ]);
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return back()->with('success', 'Pegawai berhasil dihapus.');
    }

    public function storeCasbon(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        Casbon::create($request->all());

        return back()->with('success', 'Casbon berhasil ditambahkan.');
    }
    public function showCasbonForm()
    {
        return view('dashboard.pegawai'); // buat view form di sini
    }
    public function latestHistory()
    {
        // Mengambil satu riwayat pekerjaan terbaru yang dilakukan oleh pegawai ini
        return $this->hasOne(OrderHistory::class, 'pegawai_id', 'id')->latestOfMany();
    }

}
