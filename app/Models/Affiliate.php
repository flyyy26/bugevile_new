<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 
        'nomor_whatsapp', 
        'alamat', 
        'kode',
        'nama_bank', 
        'nomor_rekening', 
        'nama_rekening'
    ];

    // Relasi ke Order
    public function orders()
    {
        // Parameter: Model Tujuan, Nama Tabel Pivot, FK di Tabel Pivot, FK Model Tujuan
        return $this->belongsToMany(Order::class, 'affiliator_order', 'affiliator_id', 'order_id')
                    ->withTimestamps(); // Agar created_at di tabel pivot terisi
    }
    public function ordersViaKode()
    {
        // Parameter: Model Tujuan, Foreign Key (di tabel orders), Local Key (di tabel affiliates)
        return $this->hasMany(Order::class, 'affiliator_kode', 'kode');
    }

    public function pelanggan(): HasMany
    {
        return $this->hasMany(Pelanggan::class, 'id_affiliates', 'id');
    }
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'id_affiliates', 'id');
    }
}