<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    protected $fillable = [
        'jenis_order_id',
        'nama',
        'harga'
    ];
    
    public function jenisOrder()
    {
        return $this->belongsTo(JenisOrder::class);
    }
}