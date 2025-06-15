<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TahunAjaran;

class Bulan extends Model
{
    protected $table = 'bulan';
    protected $fillable = ['nama', 'urutan'];

    public function tahunAjaran()
    {
        return $this->belongsToMany(
            TahunAjaran::class,
            'bulan_tahun_ajaran',
            'bulan_id',
            'tahun_ajaran_id'
        );
    }
}
