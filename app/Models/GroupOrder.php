<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_orders';
    
    protected $fillable = [
        'kode_group',
        'pelanggan_id',
        'affiliate_id',
        'nama_job',
        'grand_total',
        'dp_amount',
        'sisa_bayar',
        'harus_dibayar',
        'payment_status',
        'keterangan'
    ];

    protected $casts = [
        'grand_total' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'sisa_bayar' => 'decimal:2',
        'harus_dibayar' => 'decimal:2',
        'payment_status' => 'boolean',
    ];

    // Relasi ke pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke affiliate
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    // Relasi ke orders (satu group punya banyak order)
    public function orders()
    {
        return $this->hasMany(Order::class, 'group_order_id');
    }

    // Relasi ke pembayaran (satu group punya satu pembayaran)
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'group_order_id');
    }

    // Generate kode group otomatis
    public static function generateKodeGroup()
    {
        $prefix = 'GRP-' . date('Ymd') . '-';
        $lastGroup = self::where('kode_group', 'like', $prefix . '%')
            ->orderBy('kode_group', 'desc')
            ->first();

        if ($lastGroup) {
            $lastNumber = (int) substr($lastGroup->kode_group, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return $prefix . $nextNumber;
    }

    // Update totals dari semua order dalam group
    public function updateTotals()
    {
        $this->grand_total = $this->orders->sum('harga_jual_total');
        $this->save();
    }
}