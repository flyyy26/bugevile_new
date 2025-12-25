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
                'casbons' => $pegawai->casbons->toArray(),
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
        $availableMonths = OrderHistory::selectRaw(
            'DISTINCT strftime("%m", created_at) as month, strftime("%Y", created_at) as year'
        )
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
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'rekening' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $pegawai = Pegawai::create($request->all());

        // Ambil data rekap untuk pegawai baru (default 0 semua)
        $rekapData = (object) [
            'rekapJenis' => collect([]),
            'totalKeseluruhan' => 0,
            'totalCasbon' => 0,
            'totalSisa' => 0,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'pegawai' => $pegawai,
            'rekapData' => $rekapData
        ]);
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'pegawai' => $pegawai,
                'message' => 'Pegawai berhasil diperbarui'
            ]);
        }

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawaiName = $pegawai->nama;
        $pegawai->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pegawai "' . $pegawaiName . '" berhasil dihapus'
            ]);
        }

        return back()->with('success', 'Pegawai "' . $pegawaiName . '" berhasil dihapus.');
    }

    public function show($id)
    {
        $pegawai = Pegawai::with(['histories.order', 'casbons'])->findOrFail($id);
        
        $hargaDb = HargaJenisPekerjaan::find(1);
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

        $harga = [
            'Setting'   => $hargaDb->harga_setting,
            'Print'     => $hargaDb->harga_print,
            'Press'     => $hargaDb->harga_press,
            'Cutting'   => $hargaDb->harga_cutting,
            'Jahit'     => $hargaDb->harga_jahit,
            'Finishing' => $hargaDb->harga_finishing,
            'Packing'   => $hargaDb->harga_packing,
        ];

        $orders = Order::with('jenisOrder')->orderBy('created_at', 'desc')->get();

        $orderJenisMap = $orders->mapWithKeys(function($order) {
            return [
                $order->id => $order->jenisOrder->nama_jenis ?? 'Tidak Ada'
            ];
        })->toArray();

        $orderJenisMapNilai = $orders->mapWithKeys(function($orderNilai) {
            return [
                $orderNilai->id => $orderNilai->jenisOrder->nilai ?? 'Tidak Ada'
            ];
        })->toArray();

        $orderKonsumenMap = Order::pluck('nama_konsumen', 'id')->toArray();
        $allHistoriesForJs = $pegawai->histories->toArray();

        return view('dashboard.pegawai-detail', [
            'pegawai' => $pegawai,
            'harga' => $harga,
            'orders' => $orders,
            'orderJenisMap' => $orderJenisMap,
            'orderJenisMapNilai' => $orderJenisMapNilai,
            'orderKonsumenMap' => $orderKonsumenMap,
            'allHistoriesForJs' => $allHistoriesForJs,
        ]);
    }

    public function storeCasbon(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        $casbon = Casbon::create($request->all());
        
        // Ambil data rekap terbaru untuk hitung sisa
        // Sesuaikan dengan logika perhitungan Anda
        $pegawai = Pegawai::find($request->pegawai_id);

        // Return JSON response untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Casbon berhasil ditambahkan',
            'casbon' => $casbon,
            'pegawai_id' => $request->pegawai_id
        ]);
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
    public function getCasbonHistory(Pegawai $pegawai)
    {
        $casbons = Casbon::where('pegawai_id', $pegawai->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalCasbon = Casbon::where('pegawai_id', $pegawai->id)->sum('jumlah');
        
        return response()->json([
            'success' => true,
            'casbons' => $casbons,
            'total_casbon' => $totalCasbon
        ]);
    }

    public function deleteCasbon(Casbon $casbon)
    {
        $pegawaiId = $casbon->pegawai_id;
        $casbon->delete();
        
        // Hitung ulang total casbon
        $totalCasbon = Casbon::where('pegawai_id', $pegawaiId)->sum('jumlah');
        
        // Anda perlu menyesuaikan dengan cara menghitung total keseluruhan
        $totalKeseluruhan = 0; // Ganti dengan logika perhitungan Anda
        $totalSisa = $totalKeseluruhan - $totalCasbon;
        
        return response()->json([
            'success' => true,
            'message' => 'Casbon berhasil dihapus',
            'pegawai_id' => $pegawaiId,
            'total_casbon' => $totalCasbon,
            'total_sisa' => $totalSisa
        ]);
    }

}
