<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $fillable = [
        'kategori',
        'jumlah',
        'keterangan',
        'tanggal'
    ];
}
