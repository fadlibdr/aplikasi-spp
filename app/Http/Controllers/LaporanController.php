<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\JenisPembayaran;
use App\Models\JurnalUmum;
use Carbon\Carbon;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|operator']);
    }

    public function index()
    {
        $reportTypes = [
            'spp' => 'Pembayaran SPP',
            'keuangan' => 'Keuangan',
            'rekap-siswa' => 'Rekap per Siswa',
            'siswa' => 'Data Siswa',
            'kelas' => 'Data Kelas',
            'tahun-ajaran' => 'Data Tahun Ajaran',
            'jenis-pembayaran' => 'Data Jenis Pembayaran',
            'iuran' => 'Data Iuran',
            'pembayaran' => 'Data Pembayaran',
            'penerimaan' => 'Data Penerimaan',
            'pengeluaran' => 'Data Pengeluaran',
            'jurnal' => 'Jurnal Umum',
        ];

        return view('laporan.index', compact('reportTypes'));
    }

    public function generate(Request $request)
    {
        $v = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys($this->indexTypes())),
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        return $this->showResult($v['type'], $v['date_from'], $v['date_to']);
    }

    public function exportExcel(Request $request)
    {
        $v = $request->validate([
            'type' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $data = $this->collectData($v['type'], $v['date_from'], $v['date_to']);

        return Excel::download(
            new LaporanExport($v['type'], $v['date_from'], $v['date_to'], $data),
            "laporan-{$v['type']}_{$v['date_from']}_to_{$v['date_to']}.xlsx"
        );
    }

    public function exportPdf(Request $request)
    {
        $v = $request->validate([
            'type' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $data = $this->collectData($v['type'], $v['date_from'], $v['date_to']);

        $pdf = Pdf::loadView('laporan.pdf', [
            'type' => $v['type'],
            'from' => $v['date_from'],
            'to' => $v['date_to'],
            'data' => $data,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("laporan-{$v['type']}_{$v['date_from']}_to_{$v['date_to']}.pdf");
    }

    // Render view result
    protected function showResult(string $type, string $from, string $to)
    {
        $data = $this->collectData($type, $from, $to);

        return view('laporan.result', compact('type', 'from', 'to', 'data'));
    }

    // Semua tipe yang diizinkan
    protected function indexTypes(): array
    {
        return [
            'spp',
            'keuangan',
            'rekap-siswa',
            'siswa',
            'kelas',
            'tahun-ajaran',
            'jenis-pembayaran',
            'iuran',
            'pembayaran',
            'penerimaan',
            'pengeluaran',
            'jurnal',
        ];
    }

    // Kembalikan koleksi array scalar sesuai tipe
    protected function collectData(string $type, string $from, string $to)
    {
        $fromDT = Carbon::parse($from)->startOfDay();
        $toDT = Carbon::parse($to)->endOfDay();

        switch ($type) {
            case 'spp':
                return Pembayaran::with('iuran.siswa', 'iuran.jenisPembayaran')
                    ->whereBetween('tgl_bayar', [$fromDT, $toDT])
                    ->get()
                    ->map(fn($p) => [
                        'tgl_bayar' => $p->tgl_bayar,
                        'siswa' => $p->iuran->siswa->nama_depan . ' ' . $p->iuran->siswa->nama_belakang,
                        'jenis' => $p->iuran->jenisPembayaran->nama,
                        'jumlah' => $p->jumlah,
                    ]);

            case 'keuangan':
                $p = Penerimaan::whereBetween('tanggal', [$fromDT, $toDT])->get();
                $e = Pengeluaran::whereBetween('tanggal', [$fromDT, $toDT])->get();
                return $p->map(fn($r) => [
                    'tanggal' => $r->tanggal,
                    'keterangan' => $r->sumber,
                    'debit' => $r->jumlah,
                    'kredit' => 0,
                ])->merge(
                        $e->map(fn($x) => [
                            'tanggal' => $x->tanggal,
                            'keterangan' => $x->kategori,
                            'debit' => 0,
                            'kredit' => $x->jumlah,
                        ])
                    )->sortBy('tanggal')->values();

            case 'rekap-siswa':
                return Siswa::all()->map(fn($s) => [
                    'siswa' => $s->nama_depan . ' ' . $s->nama_belakang,
                    'dibayar' => Iuran::where('siswa_id', $s->id)
                        ->where('status', 'lunas')
                        ->whereBetween('created_at', [$fromDT, $toDT])
                        ->get()
                        ->sum(fn($i) => $i->jenisPembayaran->nominal),
                    'tunggakan' => Iuran::where('siswa_id', $s->id)
                        ->where('status', '!=', 'lunas')
                        ->get()
                        ->sum(fn($i) => $i->jenisPembayaran->nominal),
                ]);

            case 'siswa':
                return Siswa::with('kelas')->get()
                    ->map(fn($s) => [
                        'id' => $s->id,
                        'nama' => $s->nama_depan . ' ' . $s->nama_belakang,
                        'kelas' => $s->kelas->nama ?? null,
                        'tanggal_lahir' => $s->tanggal_lahir,
                        'status' => $s->status_siswa,
                    ]);

            case 'kelas':
                return Kelas::with('tahunAjarans')->get()
                    ->map(fn($k) => [
                        'id' => $k->id,
                        'nama' => $k->nama,
                        'tahun_ajaran' => optional($k->tahunAjarans)->nama,
                        'kapasitas' => $k->kapasitas,
                    ]);

            case 'tahun-ajaran':
                return TahunAjaran::with('bulan')->get()
                    ->map(fn($t) => [
                        'id' => $t->id,
                        'nama' => $t->nama,
                        'semester' => $t->semester,
                        'aktif' => $t->aktif ? 'Ya' : 'Tidak',
                        'bulan' => $t->bulan->pluck('nama')->join(', '),
                    ]);

            case 'jenis-pembayaran':
                return JenisPembayaran::all()
                    ->map(fn($j) => [
                        'id' => $j->id,
                        'kode' => $j->kode,
                        'nama' => $j->nama,
                        'nominal' => $j->nominal,
                        'frekuensi' => $j->frekuensi,
                    ]);

            case 'iuran':
                return Iuran::with('siswa', 'jenisPembayaran')->get()
                    ->map(fn($i) => [
                        'id' => $i->id,
                        'siswa' => $i->siswa->nama_depan . ' ' . $i->siswa->nama_belakang,
                        'jenis_pembayaran' => $i->jenisPembayaran->nama,
                        'bulan' => $i->bulan,
                        'status' => $i->status,
                    ]);

            case 'pembayaran':
                return Pembayaran::with('iuran.siswa', 'iuran.jenisPembayaran')->get()
                    ->map(fn($p) => [
                        'id' => $p->id,
                        'order_id' => $p->order_id,
                        'siswa' => $p->iuran->siswa->nama_depan . ' ' . $p->iuran->siswa->nama_belakang,
                        'jenis' => $p->iuran->jenisPembayaran->nama,
                        'jumlah' => $p->jumlah,
                        'status' => $p->status,
                        'tgl_bayar' => $p->tgl_bayar,
                    ]);

            case 'penerimaan':
                return Penerimaan::all()
                    ->map(fn($r) => [
                        'id' => $r->id,
                        'tanggal' => $r->tanggal,
                        'sumber' => $r->sumber,
                        'jumlah' => $r->jumlah,
                    ]);

            case 'pengeluaran':
                return Pengeluaran::all()
                    ->map(fn($e) => [
                        'id' => $e->id,
                        'tanggal' => $e->tanggal,
                        'kategori' => $e->kategori,
                        'jumlah' => $e->jumlah,
                    ]);

            case 'jurnal':
                return JurnalUmum::all()
                    ->map(fn($j) => [
                        'id' => $j->id,
                        'tanggal' => $j->tanggal,
                        'keterangan' => $j->keterangan,
                        'debit' => $j->debit,
                        'kredit' => $j->kredit,
                    ]);

            default:
                return collect([]);
        }
    }
}
