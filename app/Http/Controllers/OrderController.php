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
use App\Models\GroupOrder;
use App\Models\Pembayaran;
use App\Models\OrderSpesifikasi;
use App\Models\Pelanggan;
use App\Models\KemampuanProduksi;
use App\Models\Affiliate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::all();
        $jobs = Job::latest()->get();

        $perPage = request('per_page', 6);
        
        // Get current page from request
        $currentPage = request('page', 1);
        
        // PAGINATION
        $orders = Order::with('affiliates')
                ->latest()
                ->paginate($perPage)
                ->withQueryString();
        
        // Calculate starting number based on page
        $startNumber = ($currentPage - 1) * $perPage;
        
        $kategoriList = KategoriJenisOrder::with('jenisSpek.jenisSpekDetail')->get();
        $uniqueJobs = Order::distinct('nama_job')->pluck('nama_job')->sort();
        $totals = OrderTotal::first();
        $uniqueKonsumens = Order::distinct('nama_konsumen')->pluck('nama_konsumen')->sort();
        
        $pelanggans = Pelanggan::with(['affiliate' => function($query) {
            $query->select('id', 'kode', 'nama');
        }])->get();

        $jenisBahan = JenisBahan::all();
        $jenisPola = JenisPola::all();
        $jenisKerah = JenisKerah::all();
        $jenisJahitan = JenisJahitan::all();
        
        $jenisSpek = JenisSpek::with('detail.jenisOrder')->get();
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
            'pelanggans',
            'startNumber' // Add this
        ));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== ORDER STORE PROCESS STARTED ===');
            
            // Jika data dikirim sebagai JSON
            if ($request->isJson()) {
                $data = $request->json()->all();
            } else {
                $data = $request->all();
            }

            \Log::info('Raw data received:', $data);

            // Validasi data dasar dengan Validator facade - PERBAIKI VALIDASI PEMBAYARAN
            $validator = Validator::make($data, [
                'nama_job' => 'required|string',
                'nama_konsumen' => 'required',
                'grand_total' => 'required|numeric|min:0',
                'affiliator_kode' => 'nullable|string',
                
                // Data pembayaran - diubah menjadi nullable
                'dp_amount' => 'nullable|numeric|min:0',
                'sisa_bayar' => 'nullable|numeric|min:0',
                'harus_dibayar' => 'nullable|numeric|min:0',
                'payment_status' => 'nullable|boolean',
                
                // Data per order
                'jenis_order_id' => 'required|array',
                'jenis_order_id.*' => 'required|exists:jenis_order,id',
                
                'kategori_id' => 'required|array',
                'kategori_id.*' => 'required|exists:kategori_jenis_order,id',
                
                'nama_jenis' => 'required|array',
                'nama_jenis.*' => 'required|string',
                
                'harga_jual_satuan' => 'required|array',
                'harga_jual_satuan.*' => 'required|numeric|min:0',
                
                'harga_jual_total' => 'required|array',
                'harga_jual_total.*' => 'required|numeric|min:0',
                
                'speks' => 'required|array',
                'speks.*' => 'nullable|string',
                
                'qty' => 'required|array',
                'qty.*' => 'required|integer|min:0',
                
                'sizes' => 'required|array',
            ]);

            // Cek jika validasi gagal
            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $validated = $validator->validated();

            \Log::info('Validated data:', $validated);

            // **Cari ID affiliate dari kode yang diinput user**
            $affiliateId = null;
            $affiliatorKode = $validated['affiliator_kode'] ?? null;
            
            if ($affiliatorKode) {
                $affiliate = \App\Models\Affiliate::where('kode', $affiliatorKode)->first();
                if ($affiliate) {
                    $affiliateId = $affiliate->id;
                    \Log::info("Kode affiliate '{$affiliatorKode}' ditemukan dengan ID: {$affiliateId}");
                } else {
                    \Log::warning("Kode affiliate '{$affiliatorKode}' tidak ditemukan di database");
                }
            }

            // Handle pelanggan
            $pelanggan = null;
            $pelangganId = null;
            
            \Log::info("nama_konsumen value: " . $validated['nama_konsumen']);
            
            if (is_numeric($validated['nama_konsumen'])) {
                $pelangganId = $validated['nama_konsumen'];
                $pelanggan = Pelanggan::find($pelangganId);
                
                if (!$pelanggan) {
                    throw new \Exception("Pelanggan dengan ID {$pelangganId} tidak ditemukan");
                }
                
                \Log::info("Found existing pelanggan by ID: {$pelanggan->id} - {$pelanggan->nama}");
                
                if ($affiliateId && $pelanggan) {
                    $pelanggan->id_affiliates = $affiliateId;
                    $pelanggan->save();
                    \Log::info("Updated pelanggan {$pelanggan->id} dengan id_affiliates: {$affiliateId}");
                }
                
                if (!$affiliatorKode && $pelanggan && $pelanggan->id_affiliates) {
                    $affiliateId = $pelanggan->id_affiliates;
                    $affiliate = \App\Models\Affiliate::find($affiliateId);
                    $affiliatorKode = $affiliate ? $affiliate->kode : null;
                    \Log::info("Menggunakan affiliate existing pelanggan: {$affiliatorKode}");
                }
            } else {
                $pelanggan = Pelanggan::where('nama', $validated['nama_konsumen'])->first();
                
                if ($pelanggan) {
                    $pelangganId = $pelanggan->id;
                    \Log::info("Found existing pelanggan by name: {$pelanggan->id} - {$pelanggan->nama}");
                    
                    if ($affiliateId) {
                        $pelanggan->id_affiliates = $affiliateId;
                        $pelanggan->save();
                        \Log::info("Updated pelanggan existing {$pelanggan->id} dengan id_affiliates: {$affiliateId}");
                    }
                } else {
                    $pelanggan = Pelanggan::create([
                        'nama' => $validated['nama_konsumen'],
                        'id_affiliates' => $affiliateId
                    ]);
                    $pelangganId = $pelanggan->id;
                    \Log::info("Created pelanggan baru dengan id: {$pelangganId}, id_affiliates: {$affiliateId}");
                }
            }

            // Mulai database transaction untuk konsistensi data
            DB::beginTransaction();

            $groupOrder = GroupOrder::create([
                'kode_group' => GroupOrder::generateKodeGroup(),
                'pelanggan_id' => $pelangganId,
                'affiliate_id' => $affiliateId,
                'nama_job' => $validated['nama_job'],
                'grand_total' => $validated['grand_total'],
                'dp_amount' => $validated['dp_amount'] ?? 0,
                'sisa_bayar' => $validated['sisa_bayar'] ?? 0,
                'harus_dibayar' => $validated['harus_dibayar'] ?? $validated['grand_total'],
                'payment_status' => $validated['payment_status'] ?? false,
                'keterangan' => 'Order dari multi-order form'
            ]);

            // Buat atau cari job
            $job = Job::firstOrCreate(
                ['nama_job' => $validated['nama_job']],
                ['nama_job' => $validated['nama_job']]
            );

            // Array untuk menyimpan semua order yang dibuat
            $createdOrders = [];
            $savedOrderIds = [];
            $totalOrders = count($validated['jenis_order_id']);

            // Loop melalui setiap order
            for ($i = 0; $i < $totalOrders; $i++) {
                try {
                    $jenisOrderId = $validated['jenis_order_id'][$i] ?? null;
                    $kategoriId = $validated['kategori_id'][$i] ?? null;
                    $namaJenis = $validated['nama_jenis'][$i] ?? '';
                    
                    \Log::info("Processing order {$i}: jenis_order_id={$jenisOrderId}, nama_jenis={$namaJenis}");

                    if (!$jenisOrderId) {
                        \Log::warning("Skipping order {$i} - jenis_order_id is null");
                        continue;
                    }

                    // Ambil qty untuk order ini
                    $qty = $validated['qty'][$jenisOrderId] ?? 0;
                    
                    \Log::info("Order {$i} - jenis_order_id: {$jenisOrderId}, qty: {$qty}");

                    if ($qty < 1) {
                        \Log::warning("Skipping order {$i} with jenis_order_id {$jenisOrderId} - qty is 0");
                        continue;
                    }

                    // Cari jenis order
                    $jenisOrder = JenisOrder::find($jenisOrderId);
                    if (!$jenisOrder) {
                        throw new \Exception("Jenis order dengan ID {$jenisOrderId} tidak ditemukan");
                    }

                    $hargaSatuan = $validated['harga_jual_satuan'][$i] ?? 0;
                    $hargaJualTotal = $validated['harga_jual_total'][$i] ?? 0;

                    $kemampuanPrint = KemampuanProduksi::where('nama_kemampuan', 'Print')->first();
                    $printPerHari = $kemampuanPrint ? $kemampuanPrint->nilai_kemampuan : 30; 

                    $kemampuanPacking = KemampuanProduksi::where('nama_kemampuan', 'Packing & Finishing')->first();
                    $packingPerHari = $kemampuanPacking ? $kemampuanPacking->nilai_kemampuan : 25;

                    // Hitung hari produksi print
                    $hariPrint = $qty > 0 ? round($qty / $printPerHari, 1) : 0;

                    // Packing & finishing adalah nilai tetap (tidak dibagi)
                    $hariPacking = $packingPerHari;

                    // Total hari = print + packing & finishing (nilai tetap)
                    $hari = $hariPrint + $hariPacking;
                    $deadline = $hari;

                    // Hitung laba bersih affiliate
                    $labaBersihPerUnit = $jenisOrder->komisi_affiliate ?? 0;
                    $labaBersihAffiliate = $labaBersihPerUnit * $qty;

                    // Generate keterangan dari speks
                    $keterangan = '';
                    $speksData = [];
                    
                    try {
                        $speksJson = $validated['speks'][$i] ?? '{}';
                        $speksData = json_decode($speksJson, true);
                        
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            \Log::warning("Invalid JSON for speks at index {$i}: " . json_last_error_msg());
                            $speksData = [];
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Error parsing speks JSON: " . $e->getMessage());
                        $speksData = [];
                    }
                    
                    if (is_array($speksData) && count($speksData) > 0) {
                        $keteranganParts = [];
                        
                        foreach ($speksData as $spekId => $detailIds) {
                            $spek = JenisSpek::find($spekId);
                            $spekName = $spek ? $spek->nama_jenis_spek : "Spek {$spekId}";
                            
                            $detailNames = [];
                            if (is_array($detailIds)) {
                                foreach ($detailIds as $detailId) {
                                    $detail = JenisSpekDetail::find($detailId);
                                    if ($detail) {
                                        $detailNames[] = $detail->nama_jenis_spek_detail;
                                    }
                                }
                            }
                            
                            if (count($detailNames) > 0) {
                                $keteranganParts[] = "{$spekName}: " . implode(', ', $detailNames);
                            }
                        }
                        
                        $keterangan = implode(' | ', $keteranganParts);
                    }

                    // Buat order dengan group_order_id
                    $order = Order::create([
                        'group_order_id' => $groupOrder->id, // Link ke group order
                        'nama_job' => $validated['nama_job'],
                        'nama_konsumen' => $pelanggan->nama,
                        'keterangan' => $keterangan,
                        'qty' => $qty,
                        'hari' => $hari,
                        'est' => $hari,
                        'deadline' => $deadline,
                        'jenis_order_id' => $jenisOrderId,
                        'harga_jual_satuan' => $hargaSatuan,
                        'harga_jual_total' => $hargaJualTotal,
                        'laba_bersih_affiliate' => $labaBersihAffiliate,
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
                        'affiliator_kode' => $affiliatorKode,
                    ]);

                    $createdOrders[] = $order;
                    $savedOrderIds[] = $order->id;

                    // Hubungkan affiliate jika ada
                    if ($affiliateId) {
                        $order->affiliates()->attach($affiliateId);
                        \Log::info("Attached affiliate {$affiliateId} to order {$order->id}");
                    }

                    // Buat size breakdown
                    $sizesData = $validated['sizes'][$jenisOrderId] ?? [];
                    
                    \Log::info("Creating size for order {$order->id}", $sizesData);
                    
                    Size::create([
                        'order_id' => $order->id,
                        'xs' => $sizesData['xs'] ?? 0,
                        's' => $sizesData['s'] ?? 0,
                        'm' => $sizesData['m'] ?? 0,
                        'l' => $sizesData['l'] ?? 0,
                        'xl' => $sizesData['xl'] ?? 0,
                        '2xl' => $sizesData['2xl'] ?? 0,
                        '3xl' => $sizesData['3xl'] ?? 0,
                        '4xl' => $sizesData['4xl'] ?? 0,
                        '5xl' => $sizesData['5xl'] ?? 0,
                        '6xl' => $sizesData['6xl'] ?? 0,
                    ]);

                    // Simpan spesifikasi
                    if (is_array($speksData) && count($speksData) > 0) {
                        foreach ($speksData as $jenis_spek_id => $jenis_spek_detail_ids) {
                            if (!is_array($jenis_spek_detail_ids)) {
                                $jenis_spek_detail_ids = [$jenis_spek_detail_ids];
                            }
                            
                            foreach ($jenis_spek_detail_ids as $jenis_spek_detail_id) {
                                if (!$jenis_spek_detail_id) continue;
                                
                                OrderSpesifikasi::create([
                                    'order_id' => $order->id,
                                    'jenis_spek_id' => $jenis_spek_id,
                                    'jenis_spek_detail_id' => $jenis_spek_detail_id,
                                ]);
                            }
                        }
                        \Log::info("Created spesifikasi for order {$order->id}");
                    }

                    // Hubungkan pelanggan dengan order
                    DB::table('pelanggan_orders')->insert([
                        'pelanggan_id' => $pelanggan->id,
                        'order_id' => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    \Log::info("Order {$i} created successfully", [
                        'order_id' => $order->id,
                        'group_order_id' => $groupOrder->id,
                        'jenis_order_id' => $jenisOrderId,
                        'nama_jenis' => $namaJenis,
                        'qty' => $qty,
                        'harga_total' => $hargaJualTotal,
                    ]);

                } catch (\Exception $e) {
                    \Log::error("Error creating order at index {$i}: " . $e->getMessage(), [
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // Di bagian penyimpanan pembayaran, ganti dengan:
            if (isset($validated['dp_amount']) && $validated['dp_amount'] > 0) {
                $dpAmount = $validated['dp_amount'];
                $sisaBayar = $validated['sisa_bayar'] ?? 0;
                $harusDibayar = $validated['harus_dibayar'] ?? $validated['grand_total'];
                $paymentStatus = $validated['payment_status'] ?? ($sisaBayar <= 0);
                
                // Simpan pembayaran untuk setiap order
                foreach ($createdOrders as $order) {
                    // Hitung proporsi untuk setiap order
                    $orderTotal = $order->harga_jual_total;
                    $proportion = $orderTotal / $validated['grand_total'];
                    
                    $orderDP = round($dpAmount * $proportion);
                    $orderSisa = round($sisaBayar * $proportion);
                    $orderHarus = round($harusDibayar * $proportion);
                    
                    \App\Models\Pembayaran::create([
                        'pelanggan_id' => $pelanggan->id,
                        'order_id' => $order->id, // Wajib diisi
                        'group_order_id' => $groupOrder->id, // Optional
                        'dp' => $orderDP,
                        'sisa_bayar' => $orderSisa,
                        'harus_dibayar' => $orderHarus,
                        'status' => $paymentStatus,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                \Log::info("Payment records created for {$groupOrder->id}");
            }

            // Jika tidak ada order yang dibuat
            if (count($createdOrders) === 0) {
                throw new \Exception('Tidak ada order yang berhasil dibuat. Pastikan minimal ada satu order dengan qty > 0.');
            }

            // Commit transaction jika semua sukses
            DB::commit();

            // Update totals
            $this->updateTotals();

            \Log::info("=== ORDER STORE PROCESS COMPLETED SUCCESSFULLY ===");
            \Log::info("Total orders created: " . count($createdOrders));
            \Log::info("Pelanggan ID: {$pelangganId}");
            \Log::info("Affiliate ID: " . ($affiliateId ?? 'null'));
            \Log::info("Payment data saved: DP = {$dpAmount}, Sisa = {$sisaBayar}, Status = " . ($paymentStatus ? 'Lunas' : 'Belum Lunas'));

            return response()->json([
                'success' => true,
                'message' => count($createdOrders) . ' order berhasil ditambahkan dalam group order!',
                'pelanggan_id' => $pelangganId,
                'group_order_id' => $groupOrder->id,
                'kode_group' => $groupOrder->kode_group,
                'order_ids' => $savedOrderIds
            ]);

        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            \Log::error('Error creating orders: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) 
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete(); 

            // Deteksi AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dihapus'
                ]); 
            }

            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus orders: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus orders: ' . $e->getMessage());
        }
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

    // di OrderController.php
    public function indexGroupOrders(Request $request)
    {
        // Query dasar
        $query = GroupOrder::with(['pelanggan', 'affiliate'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
        
        // Filter berdasarkan status pembayaran
        if ($request->has('payment_status')) {
            $status = $request->payment_status === 'lunas' ? true : false;
            $query->where('payment_status', $status);
        }
        
        // Filter berdasarkan kode group atau nama pelanggan
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_group', 'like', "%{$search}%")
                ->orWhereHas('pelanggan', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            });
        }
        
        // Pagination
        $groupOrders = $query->paginate(20);
        
        // Stats untuk dashboard
        $stats = [
            'total_groups' => GroupOrder::count(),
            'total_pending' => GroupOrder::where('payment_status', false)->count(),
            'total_paid' => GroupOrder::where('payment_status', true)->count(),
            'total_revenue' => GroupOrder::sum('grand_total'),
            'today_groups' => GroupOrder::whereDate('created_at', today())->count(),
        ];
        
        return view('dashboard.group-order', compact('groupOrders', 'stats', 'request'));
    }

    public function showGroupOrder($id)
    {
        $groupOrder = GroupOrder::with([
            'pelanggan',
            'affiliate',
            'orders.size',
            'orders.jenisOrder',
            'orders.spesifikasi.jenisSpek',
            'orders.spesifikasi.jenisSpekDetail',
            'pembayaran'
        ])->findOrFail($id);
        
        // Hitung total dari semua order dalam group
        $totalHargaGroup = $groupOrder->orders->sum('harga_jual_total');
        
        // Ambil semua nama_job yang unik dalam group
        $semuaNamaJob = $groupOrder->orders
            ->pluck('nama_job')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        return view('dashboard.group-order-show', compact(
            'groupOrder',
            'totalHargaGroup',
            'semuaNamaJob'
        ));
    }

    // di OrderController.php
    public function exportGroupOrders(Request $request)
    {
        // Query sama dengan index
        $query = GroupOrder::with(['pelanggan', 'affiliate'])
            ->orderBy('created_at', 'desc');
        
        // Filter (sama seperti index)
        if ($request->has('start_date') && $request->has('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
        
        if ($request->has('payment_status')) {
            $status = $request->payment_status === 'lunas' ? true : false;
            $query->where('payment_status', $status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_group', 'like', "%{$search}%")
                ->orWhereHas('pelanggan', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            });
        }
        
        $groupOrders = $query->get();
        
        // Generate CSV/Excel
        $filename = 'group-orders-' . date('Y-m-d-H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'Kode Group',
            'Pelanggan',
            'Sales',
            'Total Harga',
            'DP',
            'Sisa Bayar',
            'Status',
            'Tanggal',
            'Jumlah Order'
        ]);
        
        // Data
        foreach ($groupOrders as $group) {
            fputcsv($output, [
                $group->kode_group,
                $group->pelanggan->nama ?? '-',
                $group->affiliate->nama ?? '-',
                $group->grand_total,
                $group->dp_amount,
                $group->sisa_bayar,
                $group->payment_status ? 'LUNAS' : 'BELUM LUNAS',
                $group->created_at->format('d/m/Y H:i'),
                $group->orders_count ?? 0
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function markAsPaid($id)
    {
        try {
            $groupOrder = GroupOrder::findOrFail($id);
            
            DB::beginTransaction();
            
            // Update group order
            $groupOrder->update([
                'payment_status' => true,
                'sisa_bayar' => 0,
                'dp_amount' => $groupOrder->harus_dibayar
            ]);
            
            // Update pembayaran jika ada
            if ($groupOrder->pembayaran) {
                $groupOrder->pembayaran->update([
                    'status' => true,
                    'sisa_bayar' => 0,
                    'dp' => $groupOrder->harus_dibayar
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Group order berhasil ditandai sebagai LUNAS'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

}