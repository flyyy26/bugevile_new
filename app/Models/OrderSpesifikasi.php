<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSpesifikasi extends Model
{
    use HasFactory;

    protected $table = 'order_spesifikasi';

    protected $fillable = [
        'order_id',
        'jenis_spek_id',
        'jenis_spek_detail_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function jenisSpek()
    {
        return $this->belongsTo(JenisSpek::class, 'jenis_spek_id');
    }

    public function jenisSpekDetail()
    {
        return $this->belongsTo(JenisSpekDetail::class, 'jenis_spek_detail_id');
    }
}
