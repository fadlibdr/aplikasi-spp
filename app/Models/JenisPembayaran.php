<?php
// app/Models/JenisPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Iuran;

class JenisPembayaran extends Model
{
    // Tabel memang bernama 'jenis_pembayaran'
    protected $table = 'jenis_pembayaran';

    protected $fillable = ['kode', 'nama', 'nominal', 'frekuensi'];

    /**
     * Relasi ke Iuran (hasMany)
     */
    public function iuran()
    {
        return $this->hasMany(Iuran::class, 'jenis_pembayaran_id');
    }
}
