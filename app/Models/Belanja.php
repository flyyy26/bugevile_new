<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belanja extends Model
{
    use HasFactory;

    protected $table = 'tb_belanja';

    protected $fillable = [
        'jenis_order_id',
        'bahan',
        'bahan_harga',
        'kertas',
        'kertas_harga',
    ];

    // Relasi ke JenisOrder (satu belanja punya satu jenis order)
    public function jenisOrder()
    {
        return $this->belongsTo(JenisOrder::class);
    }

    // Relasi ke Asesoris (satu belanja bisa punya banyak asesoris)
    public function asesoris()
    {
        return $this->hasMany(Asesoris::class, 'belanja_id');
    }
}