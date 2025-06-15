<?php
// app/Models/Siswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\Iuran;
use App\Models\User;
use Illuminate\Support\Carbon;

class Siswa extends Model
{
    // Tabel memang bernama 'siswa'
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama_depan',
        'nama_belakang',
        'foto',
        'email',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'wali_murid',
        'kontak_wali_murid',
        'tanggal_awal_masuk',
        'status_siswa',
        'status_awal_siswa',
        'status_akhir_siswa',
        'kelas_id',
    ];

    /**
     * Relasi ke Kelas (belongsTo)
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke Iuran (hasMany)
     */
    public function iuran()
    {
        return $this->hasMany(Iuran::class, 'siswa_id');
    }

    protected static function booted()
    {
        static::created(function (Siswa $siswa) {
            $birth = $siswa->tanggal_lahir
                ? Carbon::parse($siswa->tanggal_lahir)->format('dmy')
                : '';
            $password = $siswa->nama_depan . $birth;

            $user = User::create([
                'name' => $siswa->nama_depan . ' ' . $siswa->nama_belakang,
                'email' => $siswa->email,
                'password' => $password,
            ]);

            $user->assignRole('siswa');
        });
    }
}
