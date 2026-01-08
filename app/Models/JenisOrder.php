<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisOrder extends Model
{
    protected $table = 'jenis_order';

    protected $fillable = [
        'nama_jenis',
        'nilai',
        'id_kategori_jenis_order',
        'harga_barang',
        'harga_jual',
        'persentase_affiliate',
        'laba_bersih',
        'laba_kotor',
        'komisi_affiliate',
        'harga_jenis_pekerjaan_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'jenis_order_id');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriJenisOrder::class, 'id_kategori_jenis_order');
    }
    public function hargaJenisPekerjaan()
    {
        return $this->belongsTo(HargaJenisPekerjaan::class);
    }
    public function belanja()
    {
        return $this->hasOne(Belanja::class, 'jenis_order_id');
    }
    public function biaya()
    {
        return $this->hasMany(Biaya::class, 'jenis_order_id', 'id');
    }
    public function ordersCalo()
    {
        return $this->hasMany(Order::class, 'jenis_order_id')
                    ->whereNotNull('affiliator_kode'); // Pastikan ini whereNotNull
    }

    // Relasi untuk order tanpa affiliator (langsung)
    public function ordersDirect()
    {
        return $this->hasMany(Order::class, 'jenis_order_id')
                    ->whereNull('affiliator_kode');
    }
}
