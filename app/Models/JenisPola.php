<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPola extends Model
{
    protected $table = 'jenis_pola';
    protected $fillable = ['nama', 'gambar'];
}