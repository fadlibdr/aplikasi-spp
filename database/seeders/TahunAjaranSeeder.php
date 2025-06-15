<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua ID bulan dari tabel 'bulan'
        $bulanIds = DB::table('bulan')->pluck('id')->toArray();

        $years = [
            ['nama' => '2024/2025', 'semester' => 'Ganjil', 'aktif' => false],
            ['nama' => '2025/2026', 'semester' => 'Genap', 'aktif' => true],
        ];

        foreach ($years as $y) {
            $taId = DB::table('tahun_ajaran')->insertGetId([
                'nama' => $y['nama'],
                'semester' => $y['semester'],
                'aktif' => $y['aktif'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert pivot bulan_tahun_ajaran
            foreach ($bulanIds as $idBulan) {
                DB::table('bulan_tahun_ajaran')->insert([
                    'tahun_ajaran_id' => $taId,
                    'bulan_id' => $idBulan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
