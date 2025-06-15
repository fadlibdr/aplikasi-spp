<?php
// app/Models/Iuran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;
use App\Models\JenisPembayaran;

class Iuran extends Model
{
    // Tabel memang bernama 'iuran'
    protected $table = 'iuran';

    protected $fillable = [
        'siswa_id',
        'jenis_pembayaran_id',
        'bulan',
        'status',
    ];

    /**
     * Relasi ke Siswa (belongsTo)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke JenisPembayaran (belongsTo)
     */
    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenis_pembayaran_id');
    }
}
