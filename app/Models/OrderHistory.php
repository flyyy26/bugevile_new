<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Pegawai (Agar nanti bisa panggil $history->pegawai->nama)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function jenisOrder()
    {
        return $this->belongsTo(JenisOrder::class, 'jenis_order_id');
    }
}