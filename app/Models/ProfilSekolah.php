<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'telepon',
        'kepala_sekolah',
        'logo',
    ];
}
