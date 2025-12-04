<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriJenisOrder extends Model
{
    protected $table = 'kategori_jenis_order';

    protected $fillable = [
        'nama',
    ];

    public function jenisSpek()
    {
        return $this->hasMany(JenisSpek::class, 'id_kategori_jenis_order');
    }

}
