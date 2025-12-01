<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaJenisPekerjaan extends Model
{
    protected $table = 'harga_jenis_pekerjaan';

    protected $fillable = [
        'harga_setting',
        'harga_print',
        'harga_press',
        'harga_cutting',
        'harga_jahit',
        'harga_finishing',
        'harga_packing',
    ];
}
