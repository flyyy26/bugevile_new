<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSpek extends Model
{
    protected $table = 'jenis_spek';

    protected $fillable = [
        'nama_jenis_spek',
        'id_kategori_jenis_order'
    ];

    public function kategoriJenisOrder()
    {
        return $this->belongsTo(KategoriJenisOrder::class, 'id_kategori_jenis_order');
    }

    public function detail()
    {
        return $this->hasMany(JenisSpekDetail::class, 'id_jenis_spek');
    }
    public function jenisSpekDetail()
    {
        return $this->hasMany(JenisSpekDetail::class, 'id_jenis_spek');
    }

}
