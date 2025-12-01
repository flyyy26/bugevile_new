<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisJahitan extends Model
{
    protected $table = 'jenis_jahitan';
    protected $fillable = ['nama', 'gambar'];
}
