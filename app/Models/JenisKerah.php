<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKerah extends Model
{
    protected $table = 'jenis_kerah';
    protected $fillable = ['nama', 'gambar'];
}