<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Casbon extends Model
{
    protected $fillable = ['pegawai_id', 'jumlah', 'keterangan', 'tanggal'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}