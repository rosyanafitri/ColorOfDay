<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Palette extends Model
{
    protected $fillable = [
        'nama_palette',
        'kategori',
        'array_warna',
    ];

    protected $casts = [
        'array_warna' => 'array',
    ];
}