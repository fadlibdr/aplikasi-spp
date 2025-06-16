<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalUmum;
use App\Exports\JurnalUmumExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class JurnalUmumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|operator']);
    }

    public function index()
    {
        $entries = JurnalUmum::orderByDesc('tanggal')->get();
        return view('jurnal-umum.index', compact('entries'));
    }

    public function create()
    {
        return view('jurnal-umum.form', ['entry' => new JurnalUmum]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'debit' => 'required|numeric|min:0',
            'kredit' => 'required|numeric|min:0',
        ]);

        JurnalUmum::create($v);

        return redirect()
            ->route('jurnal-umum.index')
            ->with('success', 'Entri jurnal berhasil disimpan.');
    }

    /** EXPORT EXCEL */
    public function exportExcel()
    {
        return Excel::download(new JurnalUmumExport, 'jurnal-umum.xlsx');
    }

    /** EXPORT PDF */
    public function exportPdf()
    {
        $entries = JurnalUmum::orderByDesc('tanggal')->get();
        $qr = base64_encode(
            QrCode::format('png')->size(150)->generate(route('api.jurnal-umum'))
        );
        $pdf = Pdf::loadView('jurnal-umum.pdf', compact('entries', 'qr'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('jurnal-umum.pdf');
    }

    /**
     * Public API returning JSON jurnal umum entries
     */
    public function apiData(): JsonResponse
    {
        return response()->json(
            JurnalUmum::orderByDesc('tanggal')->get()
        );
    }
}
