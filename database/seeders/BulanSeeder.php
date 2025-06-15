<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulanSeeder extends Seeder
{
    public function run()
    {
        DB::table('bulan')->truncate();

        $names = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        foreach ($names as $i => $nama) {
            DB::table('bulan')->insert([
                'urutan' => $i + 1,
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
