<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'id_affiliates'
    ];

    // App\Models\Pelanggan.php

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'pelanggan_orders');
    }
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'id_affiliates', 'id');
    }
    // Relasi ke pembayaran
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Total DP pelanggan
    public function totalDp()
    {
        return $this->pembayarans()->sum('dp');
    }

    // Total sisa bayar pelanggan
    public function totalSisaBayar()
    {
        return $this->pembayarans()->sum('sisa_bayar');
    }

}