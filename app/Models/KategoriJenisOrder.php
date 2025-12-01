<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriJenisOrder extends Model
{
    protected $table = 'kategori_jenis_order';

    protected $fillable = [
        'nama',
    ];
}
