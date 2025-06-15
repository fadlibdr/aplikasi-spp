<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Models\Pembayaran;
use App\Exports\KeuanganExport;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('keuangan.index', [
            'penerimaan' => Penerimaan::with('pembayaran')->orderBy('tanggal', 'desc')->get(),
            'pengeluaran' => Pengeluaran::orderBy('tanggal', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view('keuangan.form');
    }

    public function store(Request $request)
    {
        if ($request->tipe === 'penerimaan') {
            Penerimaan::create([
                'sumber' => $request->input('sumber'),
                'jumlah' => $request->input('jumlah'),
                'keterangan' => $request->input('keterangan'),
                'tanggal' => $request->input('tanggal'),
                // pembayaran_id NULL untuk input manual
            ]);
        } else {
            Pengeluaran::create([
                'kategori' => $request->input('kategori'),
                'jumlah' => $request->input('jumlah'),
                'keterangan' => $request->input('keterangan'),
                'tanggal' => $request->input('tanggal'),
            ]);
        }
        return redirect()->route('keuangan.index')->with('success', 'Transaksi berhasil disimpan.');
    }


    public function exportExcel()
    {
        return Excel::download(new KeuanganExport, 'keuangan.xlsx');
    }

    public function exportPdf()
    {
        $penerimaan = Penerimaan::with('pembayaran')->orderBy('tanggal', 'desc')->get();
        $pengeluaran = Pengeluaran::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('keuangan.pdf', compact('penerimaan', 'pengeluaran'));
        return $pdf->download('laporan-keuangan.pdf');
    }


}
