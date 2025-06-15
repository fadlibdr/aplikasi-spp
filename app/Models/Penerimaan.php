<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';
    protected $fillable = [
        'pembayaran_id',
        'sumber',
        'jumlah',
        'keterangan',
        'tanggal'
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
