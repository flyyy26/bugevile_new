<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTotal extends Model
{
    protected $fillable = [
        'total_qty',
        'total_hari',
        'total_deadline',
        'total_print',
        'total_press',
        'total_setting',
        'total_cutting',
        'total_jahit',
        'total_finishing',
        'total_packing',
        'total_sisa_print',
        'total_sisa_press',
        'total_sisa_setting',
        'total_sisa_cutting',
        'total_sisa_jahit',
        'total_sisa_finishing',
        'total_sisa_packing',
    ];

    public function getLatestSettingHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai') // Eager load data pegawai di sini
            ->where('jenis_pekerjaan', 'Setting')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first(); // Ambil record terbaru
    }

    public function getLatestPrintHistoryAttribute()
    {
        // Langsung query ke tabel OrderHistory
        return \App\Models\OrderHistory::with('pegawai') // Wajib memuat pegawai
            ->where('jenis_pekerjaan', 'Print')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first(); // Ambil satu data terbaru
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Press'
    public function getLatestPressHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai')
            ->where('jenis_pekerjaan', 'Press')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first();
    }

    public function getLatestCuttingHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai')
            ->where('jenis_pekerjaan', 'Cutting')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first();
    }

    public function getLatestJahitHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai')
            ->where('jenis_pekerjaan', 'Jahit')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first();
    }

    public function getLatestFinishingHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai')
            ->where('jenis_pekerjaan', 'Finishing')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first();
    }

    public function getLatestPackingHistoryAttribute()
    {
        return \App\Models\OrderHistory::with('pegawai')
            ->where('jenis_pekerjaan', 'Packing')
            ->latest() // Urutkan berdasarkan created_at DESC
            ->first();
    }
}
