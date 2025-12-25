<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'nama_job','qty','jenis_order_id','hari','deadline','nama_konsumen','keterangan',
        'setting','print','press','cutting','jahit','finishing','packing','est',
        'sisa_print','sisa_press','sisa_cutting','sisa_jahit','sisa_finishing','sisa_packing',
        'affiliator_kode', 'status',

        // removed unused spesifikasi fields
    ];

    protected $casts = [
        'setting' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            // 1. Buat slug dasar dari nama_job
            $slug = Str::slug($order->nama_job);
            
            // 2. Cek apakah slug sudah ada di database
            // Jika ada, tambahkan suffix angka untuk membuatnya unik
            $originalSlug = $slug;
            $count = 1;
            
            while (static::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            // 3. Simpan slug yang unik ke model
            $order->slug = $slug;
        });
    }

    public function latestSettingHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
            ->where('jenis_pekerjaan', 'Setting')
            ->orderBy('id', 'desc');
    }

    public function latestPrintHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
            ->where('jenis_pekerjaan', 'Print')
            ->orderBy('id', 'desc');
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Press'
    public function latestPressHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
                    ->where('jenis_pekerjaan', 'Press')
                    ->orderBy('id', 'desc');
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Cutting'
    public function latestCuttingHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
                    ->where('jenis_pekerjaan', 'Cutting')
                    ->orderBy('id', 'desc');
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Jahit'
    public function latestJahitHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
                    ->where('jenis_pekerjaan', 'Jahit')
                    ->orderBy('id', 'desc');
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Finishing'
    public function latestFinishingHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
                    ->where('jenis_pekerjaan', 'Finishing')
                    ->orderBy('id', 'desc');
    }

    // Relasi untuk mencari riwayat TERBARU hanya untuk 'Packing'
    public function latestPackingHistory()
    {
        return $this->hasOne(\App\Models\OrderHistory::class)
                    ->where('jenis_pekerjaan', 'Packing')
                    ->orderBy('id', 'desc');
    }


    public function jenisOrder()
    {
        return $this->belongsTo(JenisOrder::class, 'jenis_order_id');
    }

    public function spesifikasi()
    {
        return $this->hasMany(\App\Models\OrderSpesifikasi::class, 'order_id');
    }

    public function size()
    {
        return $this->hasOne(\App\Models\Size::class, 'order_id');
    }

    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class, 'pelanggan_orders', 'order_id', 'pelanggan_id', 'nama_konsumen')
                    ->withTimestamps();
    }

    public function affiliates()
    {
        return $this->belongsToMany(Affiliate::class, 'affiliator_order', 'order_id', 'affiliator_id')
                    ->withTimestamps();
    }

    public function total()
    {
        return $this->hasOne(OrderTotal::class, 'order_id');
    }


}
