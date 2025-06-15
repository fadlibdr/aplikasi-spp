<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Pembayaran;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;

class PembayaranController extends Controller
{

    /**
     * GET /pembayaran
     * Alias of form(); so the existing route works.
     */
    public function index()
    {
        return $this->form();
    }


    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|operator'])
            ->except('callback');
    }

    /**
     * Tampilkan form daftar iuran yang pending
     * View: resources/views/pembayaran/form.blade.php
     */
    public function form()
    {
        $iurans = Iuran::with(['siswa', 'jenisPembayaran'])
            ->where('status', 'pending')->get();

        return view('pembayaran.form', compact('iurans'));
    }

    /**
     * Tampilkan halaman bayar untuk iuran tertentu
     * View: resources/views/pembayaran/bayar.blade.php
     */
    public function bayar($iuranId)
    {
        $iuran = Iuran::with(['siswa', 'jenisPembayaran'])->findOrFail($iuranId);

        // 1) Buat order_id yang unik dan mengandung iuran_id
        $orderId = 'SPP-' . $iuran->id . '-' . time();

        // 2) Simpan dulu record pembayaran status pending
        $payment = Pembayaran::create([
            'iuran_id' => $iuran->id,
            'order_id' => $orderId,
            'jumlah' => $iuran->jenisPembayaran->nominal,
            'metode' => 'midtrans',
            'status' => 'pending',
            'tgl_bayar' => null,
        ]);

        // 3) Siapkan Midtrans
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $iuran->jenisPembayaran->nominal,
            ],
            'customer_details' => [
                'first_name' => $iuran->siswa->nama_depan,
                'last_name' => $iuran->siswa->nama_belakang,
                'email' => $iuran->siswa->email ?? 'no-reply@example.com',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('pembayaran.bayar', compact('iuran', 'snapToken'));
    }

    /**
     * Callback Midtrans untuk update status dan simpan pembayaran
     */
    public function callback(Request $request)
    {
        $notif = $request->all();
        $serverKey = config('midtrans.server_key');

        // 1) Validasi signature
        $signature = hash(
            'sha512',
            $notif['order_id'] .
            $notif['status_code'] .
            $notif['gross_amount'] .
            $serverKey
        );
        if ($signature !== $notif['signature_key']) {
            return abort(403, 'Invalid signature');
        }

        // 2) Temukan payment record
        $payment = Pembayaran::where('order_id', $notif['order_id'])->first();
        if (!$payment) {
            // kalau mau, bisa buat baru, tapi
            return abort(404, 'Order not found');
        }

        // 3) Update payment
        $payment->update([
            'status' => $notif['transaction_status'],
            'tgl_bayar' => now(),
            'midtrans_id' => $notif['transaction_id'] ?? null,
        ]);

        // 4) Tandai iuran jadi lunas
        $payment->iuran->update(['status' => 'lunas']);

        return response()->json(['message' => 'OK']);
    }
}
