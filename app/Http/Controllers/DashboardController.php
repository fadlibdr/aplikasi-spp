<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Iuran;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        // Hitung siswa aktif
        $activeStudents = Siswa::where('status_siswa', 'aktif')->count();

        // Total pembayaran bulan ini
        $month = now()->month;
        $paymentsThisMonth = Pembayaran::whereMonth('tgl_bayar', $month)
            ->where('status', 'settlement')
            ->count();

        // Jumlah siswa dengan iuran pending
        $pendingStudents = Iuran::where('status', 'pending')
            ->distinct('siswa_id')
            ->count();

        // Total nominal pembayaran yang sudah diterima
        $totalReceived = Pembayaran::where('status', 'settlement')->sum('jumlah');

        // Total nominal pembayaran yang masih pending
        $totalPending = Pembayaran::where('status', 'pending')->sum('jumlah');

        // Payment status per siswa
        $studentsStatus = Siswa::with('kelas')
            ->withCount([
                'iuran as paid_count' => function ($q) {
                    $q->where('status', 'lunas');
                },
                'iuran as pending_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ])->get();

        return view('dashboard.admin', compact(
            'activeStudents',
            'paymentsThisMonth',
            'pendingStudents',
            'totalReceived',
            'totalPending',
            'studentsStatus'
        ));
    }

    /**
     * Siswa Dashboard
     */
    public function student()
    {
        // Cari record Siswa berdasarkan email user
        $siswa = Siswa::where('email', Auth::user()->email)->firstOrFail();

        // Ambil semua tagihan pending untuk siswa itu
        $pendingIurans = Iuran::with('jenisPembayaran')
            ->where('siswa_id', $siswa->id)
            ->where('status', 'pending')
            ->get();

        $totalPending = $pendingIurans->sum(fn($i) => $i->jenisPembayaran->nominal);

        return view('dashboard.student', compact(
            'siswa',
            'pendingIurans',
            'totalPending'
        ));
    }
}
