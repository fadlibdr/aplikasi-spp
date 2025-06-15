<?php

// database/seeders/PenerimaanPengeluaranSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class PenerimaanPengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        // Seed 5 penerimaan iuran
        for ($i = 1; $i <= 5; $i++) {
            Penerimaan::create([
                'pembayaran_id' => null, // atau isi dengan id pembayaran jika ada
                'sumber' => 'Pembayaran SPP',
                'jumlah' => 200000 + ($i * 10000),
                'keterangan' => 'SPP bulan ' . $i,
                'tanggal' => Carbon::now()->subMonths(6 - $i),
            ]);
        }
        // Seed 3 penerimaan non-iuran
        Penerimaan::create([
            'pembayaran_id' => null,
            'sumber' => 'Donasi Alumni',
            'jumlah' => 1500000,
            'keterangan' => 'Donasi kegiatan',
            'tanggal' => Carbon::now()->subMonth(),
        ]);

        // Seed 4 pengeluaran
        Pengeluaran::create([
            'kategori' => 'ATK',
            'jumlah' => 300000,
            'keterangan' => 'Pembelian alat tulis',
            'tanggal' => Carbon::now()->subWeeks(2),
        ]);
        Pengeluaran::create([
            'kategori' => 'Listrik',
            'jumlah' => 450000,
            'keterangan' => 'Tagihan listrik sekolah',
            'tanggal' => Carbon::now()->subWeeks(1),
        ]);
    }
}
