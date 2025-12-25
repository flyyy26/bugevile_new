<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaAffiliator extends Model
{
    use HasFactory;

    // Nama tabel (opsional, jika nama class sesuai standar plural, Laravel otomatis tahu)
    protected $table = 'harga_affiliator';

    protected $fillable = [
        'harga',
    ];
}