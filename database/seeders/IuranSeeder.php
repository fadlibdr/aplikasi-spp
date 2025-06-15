<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Iuran;
use App\Models\Siswa;
use App\Models\JenisPembayaran;

class IuranSeeder extends Seeder
{
    public function run()
    {
        Iuran::truncate();

        $siswaList = Siswa::all();
        $jenisList = JenisPembayaran::all();

        foreach ($siswaList as $s) {
            foreach ($jenisList as $j) {
                // Buat iuran untuk bulan 1–12
                for ($m = 1; $m <= 12; $m++) {
                    Iuran::create([
                        'siswa_id' => $s->id,
                        'jenis_pembayaran_id' => $j->id,
                        'bulan' => $m,
                        'status' => $m % 3 === 0 ? 'lunas' : 'pending',
                    ]);
                }
            }
        }
    }
}
