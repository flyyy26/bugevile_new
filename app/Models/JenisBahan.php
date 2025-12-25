<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBahan extends Model
{
    protected $table = 'jenis_bahan';
    protected $fillable = ['nama', 'gambar'];
}