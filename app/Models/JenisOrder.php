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
        'id_kategori_jenis_order'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'jenis_order_id');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriJenisOrder::class, 'id_kategori_jenis_order');
    }


}
