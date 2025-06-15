<?php
// app/Models/Kelas.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TahunAjaran;

class Kelas extends Model
{
    // Tabel memang bernama 'kelas'
    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'kapasitas',
        'tahun_ajaran_id',
    ];

    /**
     * Relasi ke TahunAjaran (belongsTo)
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
