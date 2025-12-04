<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSpekDetail extends Model
{
    protected $table = 'jenis_spek_detail';

    protected $fillable = [
        'nama_jenis_spek_detail',
        'id_jenis_spek',
        'gambar'
    ];

    public function jenisSpek()
    {
        return $this->belongsTo(JenisSpek::class, 'id_jenis_spek');
    }

    public function jenisOrder()
    {
        return $this->belongsToMany(JenisOrder::class, 'jenis_spek_detail_jenis_order');
    }
}