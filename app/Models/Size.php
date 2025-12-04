<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'order_id',
        'xs','s','m','l','xl',
        '2xl','3xl','4xl','5xl','6xl'
    ];
}