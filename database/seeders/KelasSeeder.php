<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class KelasSeeder extends Seeder
{
    public function run()
    {
        Kelas::truncate();

        $ta = TahunAjaran::where('aktif', true)->first();

        // Buat 3 kelas contoh
        $names = ['X IPA', 'X IPS', 'XI IPA'];
        foreach ($names as $nama) {
            Kelas::create([
                'nama' => $nama,
                'kapasitas' => 30,
                'tahun_ajaran_id' => $ta->id,
            ]);
        }
    }
}
