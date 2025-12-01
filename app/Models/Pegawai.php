<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderHistory;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'posisi',
        'rekening',
        'alamat'
    ];

    public function histories()
    {
        // Mencari banyak baris di OrderHistory, di mana foreign key-nya adalah 'pegawai_id'
        // dan key lokalnya adalah 'id' pegawai ini.
        return $this->hasMany(OrderHistory::class, 'pegawai_id', 'id');
    }

    public function casbons()
    {
        return $this->hasMany(Casbon::class);
    }
    public function latestHistory()
    {
        // Menggunakan hasOne dan latestOfMany() untuk efisiensi
        return $this->hasOne(OrderHistory::class, 'pegawai_id', 'id')->latestOfMany();
    }

}