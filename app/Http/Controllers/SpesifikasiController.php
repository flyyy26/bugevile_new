<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSpek;
use App\Models\JenisSpekDetail;
use App\Models\KategoriJenisOrder;
use Illuminate\Support\Facades\Storage;

class SpesifikasiController extends Controller
{
    public function index()
    {
        // Ambil kategori + speknya
        $kategori = KategoriJenisOrder::with('jenisSpek.detail')->get();
        
        // Ambil semua jenis_order dengan relasi kategorinya untuk ditampilkan di modal
        $jenisOrderList = \App\Models\JenisOrder::all();

        return view('dashboard.spesifikasi', compact('kategori', 'jenisOrderList'));
    }

    public function storeJenisSpek(Request $request)
    {
        try {
            \Log::info('storeJenisSpek called with:', $request->all());
            
            $validated = $request->validate([
                'nama_jenis_spek' => 'required|string|max:255',
                'id_kategori_jenis_order' => 'required|exists:kategori_jenis_order,id',
                'current_kategori_id' => 'required|exists:kategori_jenis_order,id'
            ]);

            \Log::info('Validation passed:', $validated);

            $spek = JenisSpek::create([
                'nama_jenis_spek' => $validated['nama_jenis_spek'],
                'id_kategori_jenis_order' => $validated['id_kategori_jenis_order']
            ]);

            \Log::info('JenisSpek created:', ['id' => $spek->id]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis spek berhasil ditambahkan',
                'spek' => [
                    'id' => $spek->id,
                    'nama_jenis_spek' => $spek->nama_jenis_spek,
                    'id_kategori_jenis_order' => $spek->id_kategori_jenis_order,
                ]
            ], 200); // Explicitly set HTTP 200

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('storeJenisSpek error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jenis spek: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyJenisSpek(Request $request, $id)
    {
        $jenisSpek = JenisSpek::findOrFail($id);
        
        // Hapus semua detail terkait
        foreach ($jenisSpek->detail as $detail) {
            // Hapus gambar jika ada
            if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                Storage::delete('public/' . $detail->gambar);
            }
            // Hapus relasi many-to-many
            $detail->jenisOrder()->detach();
        }
        
        // Hapus semua detail
        $jenisSpek->detail()->delete();
        
        // Hapus jenis spek itu sendiri
        $jenisSpek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis Spek berhasil dihapus'
        ]);
    }

    public function storeJenisSpekDetail(Request $request)
    {
        try {
            $request->validate([
                'nama_jenis_spek_detail' => 'required|string|max:255',
                'id_jenis_spek' => 'required|exists:jenis_spek,id',
                'id_jenis_order' => 'nullable|array',
                'id_jenis_order.*' => 'exists:jenis_order,id',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:7048'
            ]);

            $data = [
                'nama_jenis_spek_detail' => $request->nama_jenis_spek_detail,
                'id_jenis_spek' => $request->id_jenis_spek
            ];

            if ($request->hasFile('gambar')) {
                $data['gambar'] = $request->file('gambar')->store('jenis_spek_detail', 'public');
            }

            $detail = JenisSpekDetail::create($data);
            
            // Sync jenis_order (many-to-many)
            if ($request->id_jenis_order) {
                $detail->jenisOrder()->sync($request->id_jenis_order);
            }

            // Load relationship untuk response
            $detail->load('jenisOrder');

            // Return data lengkap untuk update UI
            return response()->json([
                'success' => true,
                'message' => 'Jenis Spek Detail berhasil ditambahkan',
                'detail' => $detail,
                'current_kategori_id' => $request->current_kategori_id,
                'current_spek_id' => $request->current_spek_id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateJenisSpekDetail(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_jenis_spek_detail' => 'required|string|max:255',
                'id_jenis_spek' => 'required|exists:jenis_spek,id',
                'id_jenis_order' => 'nullable|array',
                'id_jenis_order.*' => 'exists:jenis_order,id',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:7048'
            ]);

            $detail = JenisSpekDetail::findOrFail($id);

            // Update data
            $detail->nama_jenis_spek_detail = $request->nama_jenis_spek_detail;
            $detail->id_jenis_spek = $request->id_jenis_spek;

            // Handle gambar
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                    Storage::delete('public/' . $detail->gambar);
                }

                $detail->gambar = $request->file('gambar')->store('jenis_spek_detail', 'public');
            }

            // Hapus gambar jika ada request untuk menghapus (opsional)
            if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
                if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                    Storage::delete('public/' . $detail->gambar);
                }
                $detail->gambar = null;
            }

            $detail->save();
            
            // Sync jenis_order (many-to-many)
            if ($request->id_jenis_order) {
                $detail->jenisOrder()->sync($request->id_jenis_order);
            } else {
                $detail->jenisOrder()->detach();
            }

            // Load relationship untuk response
            $detail->load('jenisOrder');

            // Return JSON response untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis Spek Detail berhasil diupdate',
                'data' => $detail,
                'redirect_url' => url()->previous() // Opsional: URL untuk redirect
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangkap jika data tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            // Tangkap error lainnya
            Log::error('Error update jenis spek detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function destroyJenisSpekDetail($id, Request $request)
    {
        try {
            $detail = JenisSpekDetail::findOrFail($id);
            
            // Simpan data sebelum dihapus untuk response
            $currentKategoriId = $request->current_kategori_id;
            $currentSpekId = $request->current_spek_id;
            
            // Hapus gambar jika ada
            if ($detail->gambar && Storage::exists('public/' . $detail->gambar)) {
                Storage::delete('public/' . $detail->gambar);
            }
            
            // Hapus data
            $detail->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil dihapus',
                'current_kategori_id' => $currentKategoriId,
                'current_spek_id' => $currentSpekId
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting jenis spek detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus detail'
            ], 500);
        }
    }
}