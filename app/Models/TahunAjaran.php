<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    // Pakai tabel singular
    protected $table = 'tahun_ajaran';

    protected $fillable = ['nama', 'semester', 'aktif'];

    public function bulan()
    {
        return $this->belongsToMany(
            Bulan::class,
            'bulan_tahun_ajaran',
            'tahun_ajaran_id',
            'bulan_id'
        )->orderBy('urutan');
    }
}
