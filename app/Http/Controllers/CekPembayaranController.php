<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Iuran;
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

        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . uniqid(),
                'gross_amount' => $totalTagihan,
            ],
            'customer_details' => [
                'first_name' => $siswa->nama_depan,
                'last_name' => $siswa->nama_belakang,
                'email' => $siswa->email ?? 'default@email.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('cek-pembayaran.show', compact('siswa', 'iuran', 'totalTagihan', 'snapToken'));
    }

    public function bayar(Request $request)
    {
        $request->validate(['iuran_ids' => 'required|array']);

        $iurans = Iuran::with(['jenisPembayaran', 'siswa'])
            ->whereIn('id', $request->iuran_ids)
            ->where('status', 'pending')
            ->get();

        if ($iurans->isEmpty()) {
            return response()->json(['error' => 'Tagihan tidak ditemukan'], 422);
        }

        if (Auth::check() && Auth::user()->hasRole('siswa')) {
            $email = Auth::user()->email;
            foreach ($iurans as $iuran) {
                if ($iuran->siswa->email !== $email) {
                    return response()->json(['error' => 'Tagihan tidak valid'], 403);
                }
            }
        }

        $total = $iurans->sum(fn ($i) => $i->jenisPembayaran->nominal);
        $siswa = $iurans->first()->siswa;

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . uniqid(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $siswa->nama_depan,
                'last_name' => $siswa->nama_belakang,
                'email' => $siswa->email ?? 'default@email.com',
            ]
        ];

        $token = Snap::getSnapToken($params);

        return response()->json(['token' => $token]);
    }

}
