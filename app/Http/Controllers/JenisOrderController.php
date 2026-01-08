<?php

namespace App\Http\Controllers;

use App\Models\JenisOrder;
use Illuminate\Http\Request;
use App\Models\KategoriJenisOrder;
use App\Models\HargaJenisPekerjaan;
use App\Models\Belanja;
use App\Models\Asesoris; // Pastikan model ini ada

class JenisOrderController extends Controller
{
    public function index()
    {
        $jenisOrders = JenisOrder::with([
            'kategori', 
            'belanja', 
            'belanja.asesoris',
            'biaya' => function($query) {
                $query->where('jenis_order_id', '=', DB::raw('jenis_orders.id'));
            },
            'hargaJenisPekerjaan' // Pastikan relasi ini ada
        ])->get();
        
        $kategoriList = KategoriJenisOrder::all();

        // Ambil data harga jenis pekerjaan dengan ID 1
        // Gunakan first() untuk mendapatkan record pertama dengan ID 1
        $hargaJenisPekerjaanDefault = \App\Models\HargaJenisPekerjaan::where('id', 1)->first();
        
        // Jika tidak ada, buat data default
        if (!$hargaJenisPekerjaanDefault) {
            $hargaJenisPekerjaanDefault = \App\Models\HargaJenisPekerjaan::create([
                'id' => 1,
                'nama' => 'Harga Standard',
                'harga_setting' => 5000,
                'harga_print' => 10000,
                'harga_press' => 8000,
                'harga_cutting' => 3000,
                'harga_jahit' => 7000,
                'harga_finishing' => 4000,
                'harga_packing' => 2000
            ]);
        }

        return view('dashboard.orders.setting.index', compact(
            'jenisOrders', 
            'kategoriList',
            'hargaJenisPekerjaanDefault' // Pastikan ini dikirim
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_jenis' => 'required|string|max:255',
                'nilai' => 'required|numeric',
                'id_kategori_jenis_order' => 'required|exists:kategori_jenis_order,id',
                'bahan_harga' => 'nullable|numeric|min:0',
                'kertas_harga' => 'nullable|numeric|min:0'
            ]);

            // **PASTIKAN DATA HARGA JENIS PEKERJAAN ID 1 ADA**
            $hargaJenisPekerjaanModel = HargaJenisPekerjaan::firstOrCreate(
                ['id' => 1],
                [
                    'nama' => 'Harga Standard',
                    'harga_setting' => 5000,
                    'harga_print' => 10000,
                    'harga_press' => 8000,
                    'harga_cutting' => 3000,
                    'harga_jahit' => 7000,
                    'harga_finishing' => 4000,
                    'harga_packing' => 2000
                ]
            );

            // 1. Buat JenisOrder dengan harga_jenis_pekerjaan_id = 1
            $jenisOrder = JenisOrder::create([
                'nama_jenis' => $request->nama_jenis,
                'nilai' => $request->nilai,
                'id_kategori_jenis_order' => $request->id_kategori_jenis_order,
                'harga_jenis_pekerjaan_id' => 1, // Selalu 1
                'harga_barang' => 0 // sementara
            ]);

            // 2. Buat data Belanja
            $belanja = Belanja::create([
                'jenis_order_id' => $jenisOrder->id,
                'bahan_harga' => $request->bahan_harga ?? 0,
                'kertas_harga' => $request->kertas_harga ?? 0
            ]);

            // 3. Tambahkan asesoris jika ada
            $totalAsesoris = 0;
            if ($request->asesoris_nama) {
                foreach ($request->asesoris_nama as $index => $nama) {
                    $harga = $request->asesoris_harga[$index] ?? 0;
                    if ($nama && $harga > 0) {
                        $belanja->asesoris()->create([
                            'nama' => $nama,
                            'harga' => $harga,
                            'belanja_id' => $belanja->id
                        ]);
                        $totalAsesoris += $harga;
                    }
                }
            }

            // 4. Hitung total harga barang
            $bahanHarga = $belanja->bahan_harga ?? 0;
            $kertasHarga = $belanja->kertas_harga ?? 0;
            $nilai = $jenisOrder->nilai ?? 1;
            
            // **Hitung harga jenis pekerjaan dari ID 1 dengan mengalikan print dan press dengan nilai**
            $hargaJenisPekerjaanTotal = 
                ($hargaJenisPekerjaanModel->harga_setting ?? 0) +
                (($hargaJenisPekerjaanModel->harga_print ?? 0) * $nilai) + // Print dikalikan nilai
                (($hargaJenisPekerjaanModel->harga_press ?? 0) * $nilai) + // Press dikalikan nilai
                ($hargaJenisPekerjaanModel->harga_cutting ?? 0) +
                ($hargaJenisPekerjaanModel->harga_jahit ?? 0) +
                ($hargaJenisPekerjaanModel->harga_finishing ?? 0) +
                ($hargaJenisPekerjaanModel->harga_packing ?? 0);

            $hargaBarang = ($bahanHarga + $kertasHarga) * $nilai + $totalAsesoris + $hargaJenisPekerjaanTotal;
            
            // 5. Update harga barang
            $jenisOrder->harga_barang = $hargaBarang;
            $jenisOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Jenis order berhasil ditambahkan!',
                'data' => [
                    'harga_barang' => $hargaBarang,
                    'total_asesoris' => $totalAsesoris,
                    'harga_jenis_pekerjaan_total' => $hargaJenisPekerjaanTotal,
                    'nilai' => $nilai
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jenisOrder = JenisOrder::findOrFail($id);
            $nama = $jenisOrder->nama_jenis;
            $jenisOrder->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Jenis order '{$nama}' berhasil dihapus"
                ]);
            }
            
            return redirect()->back()->with('success', "Jenis order '{$nama}' berhasil dihapus");
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jenisOrder = JenisOrder::findOrFail($id);
            
            // Update data di tabel jenis_order
            $jenisOrder->nama_jenis = $request->nama_jenis;
            $jenisOrder->nilai = $request->nilai;
            $jenisOrder->id_kategori_jenis_order = $request->id_kategori_jenis_order; 
            $jenisOrder->harga_barang = 0; // sementara, nanti dihitung
            $jenisOrder->save();

            // Cari atau buat data belanja yang terkait
            $belanja = Belanja::firstOrCreate(
                ['jenis_order_id' => $jenisOrder->id],
                [
                    'bahan_harga' => $request->bahan_harga ?? 0,
                    'kertas_harga' => $request->kertas_harga ?? 0
                ]
            );

            // Update harga bahan dan kertas
            $belanja->bahan_harga = $request->bahan_harga ?? 0;
            $belanja->kertas_harga = $request->kertas_harga ?? 0;
            $belanja->save();

            // Hapus asesoris lama
            $belanja->asesoris()->delete();

            // Tambahkan asesoris baru
            $totalAsesoris = 0;
            if ($request->asesoris_nama) {
                foreach ($request->asesoris_nama as $index => $nama) {
                    $harga = $request->asesoris_harga[$index] ?? 0;
                    if ($nama && $harga > 0) {
                        $belanja->asesoris()->create([
                            'nama' => $nama,
                            'harga' => $harga,
                            'belanja_id' => $belanja->id
                        ]);
                        $totalAsesoris += $harga;
                    }
                }
            }

            // Update harga jenis pekerjaan
            if ($jenisOrder->hargaJenisPekerjaan) {
                $jenisOrder->hargaJenisPekerjaan->update([
                    'harga_setting' => $request->harga_setting ?? 0,
                    'harga_print' => $request->harga_print ?? 0,
                    'harga_press' => $request->harga_press ?? 0,
                    'harga_cutting' => $request->harga_cutting ?? 0,
                    'harga_jahit' => $request->harga_jahit ?? 0,
                    'harga_finishing' => $request->harga_finishing ?? 0,
                    'harga_packing' => $request->harga_packing ?? 0,
                ]);
            }

            // Hitung total harga barang
            $bahanHarga = $belanja->bahan_harga ?? 0;
            $kertasHarga = $belanja->kertas_harga ?? 0;
            $nilai = $jenisOrder->nilai ?? 1;

            // Hitung total harga jenis pekerjaan dengan perkalian nilai untuk print dan press
            $hargaJenisPekerjaanTotal = 0;
            if ($jenisOrder->hargaJenisPekerjaan) {
                $hargaJenisPekerjaanTotal = 
                    ($jenisOrder->hargaJenisPekerjaan->harga_setting ?? 0) +
                    (($jenisOrder->hargaJenisPekerjaan->harga_print ?? 0) * $nilai) +
                    (($jenisOrder->hargaJenisPekerjaan->harga_press ?? 0) * $nilai) +
                    ($jenisOrder->hargaJenisPekerjaan->harga_cutting ?? 0) +
                    ($jenisOrder->hargaJenisPekerjaan->harga_jahit ?? 0) +
                    ($jenisOrder->hargaJenisPekerjaan->harga_finishing ?? 0) +
                    ($jenisOrder->hargaJenisPekerjaan->harga_packing ?? 0);
            }

            $hargaBarang = ($bahanHarga + $kertasHarga) * $nilai + $totalAsesoris + $hargaJenisPekerjaanTotal;
            $jenisOrder->harga_barang = $hargaBarang;
            $jenisOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Jenis order berhasil diperbarui!',
                'data' => [
                    'harga_barang' => $hargaBarang,
                    'total_asesoris' => $totalAsesoris,
                    'harga_jenis_pekerjaan_total' => $hargaJenisPekerjaanTotal
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }

   public function updateLaba(Request $request, $id)
    {
        try {
            $jenisOrder = JenisOrder::findOrFail($id);

            // Update harga
            $jenisOrder->harga_barang = $request->harga_barang ?? 0;
            $jenisOrder->harga_jual   = $request->harga_jual ?? 0;

            // Update persentase affiliate (snapshot)
            $jenisOrder->persentase_affiliate = $request->persentase_affiliate ?? 0;

            // Reset biaya
            $jenisOrder->biaya()->delete();

            $totalBiaya = 0;
            if ($request->biaya_nama) {
                foreach ($request->biaya_nama as $i => $nama) {
                    $harga = $request->biaya_harga[$i] ?? 0;
                    if ($nama && $harga > 0) {
                        $jenisOrder->biaya()->create([
                            'nama' => $nama,
                            'harga' => $harga
                        ]);
                        $totalBiaya += $harga;
                    }
                }
            }

            // ================= HITUNG LABA =================

            $labaKotor = $jenisOrder->harga_jual - $jenisOrder->harga_barang;
            $labaBersihSebelumKomisi = $labaKotor - $totalBiaya;

            $komisiAffiliate = (
                $labaBersihSebelumKomisi * $jenisOrder->persentase_affiliate
            ) / 100;

            $labaBersihSetelahKomisi = $labaBersihSebelumKomisi - $komisiAffiliate;

            // ================= SIMPAN =================

            $jenisOrder->update([
                'laba_kotor'        => $labaKotor,
                'laba_bersih'       => $labaBersihSetelahKomisi,
                'komisi_affiliate'  => $komisiAffiliate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laba berhasil diperbarui',
                'data' => [
                    'laba_kotor' => $labaKotor,
                    'laba_bersih' => $labaBersihSetelahKomisi,
                    'komisi_affiliate' => $komisiAffiliate,
                    'persentase_affiliate' => $jenisOrder->persentase_affiliate,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}