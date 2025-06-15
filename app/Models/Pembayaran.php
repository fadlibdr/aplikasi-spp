<?php
// app/Models/Pembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Iuran;

class Pembayaran extends Model
{
    // Tabel memang bernama 'pembayaran'
    protected $table = 'pembayaran';

    protected $fillable = [
        'iuran_id',
        'order_id',
        'jumlah',
        'metode',
        'midtrans_id',
        'tgl_bayar',
        'status',
    ];

    /**
     * Relasi ke Iuran (belongsTo)
     */
    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'iuran_id');
    }

    public function siswa()
    {
        return $this->hasOneThrough(Siswa::class, Iuran::class, 'id', 'id', 'iuran_id', 'siswa_id');
    }
}
