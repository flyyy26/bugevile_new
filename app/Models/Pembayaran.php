<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'tb_pembayaran';

    // Fillable fields
    protected $fillable = [
        'pelanggan_id',
        'group_order_id',
        'order_id',
        'dp',
        'sisa_bayar',
        'harus_dibayar',
        'status'
    ];

    // Casting
    protected $casts = [
        'dp' => 'decimal:2',
        'sisa_bayar' => 'decimal:2',
        'harus_dibayar' => 'decimal:2',
        'status' => 'boolean'
    ];

    // Dates
    protected $dates = ['created_at', 'updated_at'];

    public function groupOrder()
    {
        return $this->belongsTo(GroupOrder::class);
    }

    // Relasi ke pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Cek apakah pembayaran sudah lunas
    public function isLunas()
    {
        return $this->status === true;
    }

    // Cek apakah pembayaran belum lunas
    public function isBelumLunas()
    {
        return $this->status === false;
    }

    // Update status otomatis berdasarkan sisa_bayar
    public function updateStatusAuto()
    {
        $this->status = ($this->sisa_bayar <= 0);
        $this->save();
        return $this;
    }

    // Method untuk update pembayaran
    public function updatePembayaran($data)
    {
        $oldSisaBayar = $this->sisa_bayar;
        $newSisaBayar = $data['sisa_bayar'];
        
        // Hitung berapa yang dibayarkan
        $pembayaranDiterima = $oldSisaBayar - $newSisaBayar;
        
        if ($pembayaranDiterima > 0) {
            // Update DP (tambahkan pembayaran yang diterima)
            $this->dp += $pembayaranDiterima;
        }
        
        // Update sisa bayar
        $this->sisa_bayar = $newSisaBayar;
        
        // Update status otomatis
        $this->updateStatusAuto();
        
        return $this;
    }

    // Method untuk melunasi semua
    public function lunasiSemua()
    {
        $this->dp += $this->sisa_bayar;
        $this->sisa_bayar = 0;
        $this->status = true;
        $this->save();
        
        return $this;
    }
}