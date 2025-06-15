<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembayaran;

class JenisPembayaranSeeder extends Seeder
{
    public function run()
    {
        JenisPembayaran::truncate();

        $data = [
            ['kode' => 'SPP', 'nama' => 'SPP Bulanan', 'nominal' => 150000, 'frekuensi' => 'Bulanan'],
            ['kode' => 'DINAS', 'nama' => 'Uang Dinas', 'nominal' => 50000, 'frekuensi' => 'Bulanan'],
            ['kode' => 'UKS', 'nama' => 'Uang UKS', 'nominal' => 20000, 'frekuensi' => 'Tahunan'],
        ];
        foreach ($data as $d) {
            JenisPembayaran::create($d);
        }
    }
}
