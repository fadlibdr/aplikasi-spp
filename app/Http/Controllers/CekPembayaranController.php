<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Iuran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;


class CekPembayaranController extends Controller
{
    public function index()
    {
        $nisn = null;
        if (Auth::check() && Auth::user()->hasRole('siswa')) {
            $nisn = Siswa::where('email', Auth::user()->email)->value('nisn');
        }
        return view('cek-pembayaran.index', compact('nisn'));
    }

    public function show(Request $request)
    {
        $request->validate(['nisn' => 'required']);

        if (Auth::check() && Auth::user()->hasRole('siswa')) {
            $siswa = Siswa::where('email', Auth::user()->email)->firstOrFail();
            if ($request->nisn !== $siswa->nisn) {
                return back()->withErrors('Anda hanya dapat mengecek pembayaran anda sendiri.');
            }
        } else {
            $siswa = Siswa::where('nisn', $request->nisn)->firstOrFail();
        }
        $iuran = Iuran::with('jenisPembayaran')
            ->where('siswa_id', $siswa->id)
            ->where('status', 'pending')
            ->get();

        $totalTagihan = $iuran->sum(fn($i) => $i->jenisPembayaran->nominal);

        return view('cek-pembayaran.show', compact('siswa', 'iuran', 'totalTagihan'));
    }

    public function pay(Request $request)
    {
        $request->validate([
            'iuran_ids' => 'required|array|min:1',
            'iuran_ids.*' => 'exists:iuran,id',
        ]);

        $iurans = Iuran::with(['siswa', 'jenisPembayaran'])
            ->whereIn('id', $request->iuran_ids)
            ->where('status', 'pending')
            ->get();

        if ($iurans->isEmpty()) {
            return response()->json(['message' => 'Tagihan tidak ditemukan'], 404);
        }

        $siswa = $iurans->first()->siswa;
        $totalTagihan = $iurans->sum(fn($i) => $i->jenisPembayaran->nominal);
        $idsString = $iurans->pluck('id')->implode(',');
        $orderId = 'MULTI-' . $idsString . '-' . time();

        // Simpan pembayaran pending
        Pembayaran::create([
            'iuran_id' => $iurans->first()->id,
            'order_id' => $orderId,
            'jumlah' => $totalTagihan,
            'metode' => 'midtrans',
            'status' => 'pending',
        ]);

        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalTagihan,
            ],
            'customer_details' => [
                'first_name' => $siswa->nama_depan,
                'last_name' => $siswa->nama_belakang,
                'email' => $siswa->email ?? 'default@email.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }

}
