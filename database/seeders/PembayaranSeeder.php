<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Iuran;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        Pembayaran::truncate();

        $paidIurans = Iuran::where('status', 'lunas')->take(50)->get();
        foreach ($paidIurans as $i) {
            Pembayaran::create([
                'iuran_id' => $i->id,
                'order_id' => 'ORDER-' . $i->id . '-' . time(),
                'jumlah' => $i->jenisPembayaran->nominal,
                'metode' => 'manual',
                'midtrans_id' => null,
                'tgl_bayar' => now(),
                'status' => 'settlement',
            ]);
        }
    }
}
