<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesoris extends Model
{
    use HasFactory;

    protected $table = 'tb_asesoris';

    protected $fillable = [
        'belanja_id',
        'nama',
        'harga',
    ];

    // Relasi ke Belanja
    public function belanja()
    {
        return $this->belongsTo(Belanja::class, 'belanja_id');
    }
}