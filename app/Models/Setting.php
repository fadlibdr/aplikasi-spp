<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Jika nama tabel bukan 'settings', ganti di bawah ini:
    // protected $table = 'nama_tabel_kamu';

    // Kita gunakan kolom timestamp untuk created_at/updated_at
    public $timestamps = true;

    // Hanya field key/value yang boleh diisi massal
    protected $fillable = ['key', 'value'];
}
