<?php
// app/Http/Controllers/RiwayatTransaksiController.php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('iuran.jenisPembayaran', 'iuran.siswa')
            ->when(Auth::user()->hasRole('siswa'), function ($query) {
                $query->whereHas('iuran.siswa', fn($q) => $q->where('email', Auth::user()->email));
            })
            ->orderByDesc('tgl_bayar')
            ->paginate(10);

        return view('riwayat.index', compact('pembayaran'));
    }

    public function show($id)
    {
        $data = Pembayaran::with('iuran.jenisPembayaran', 'iuran.siswa')->findOrFail($id);
        return view('riwayat.show', compact('data'));
    }
}
