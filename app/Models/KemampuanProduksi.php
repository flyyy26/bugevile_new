<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemampuanProduksi extends Model
{
    use HasFactory;

    protected $table = 'tb_kemampuan_produksi';

    protected $fillable = [
        'nama_kemampuan',
        'nilai_kemampuan'
    ];
}