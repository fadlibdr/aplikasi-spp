<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\Bulan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TahunAjaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view tahun_ajaran')->only('index');
        $this->middleware('permission:create tahun_ajaran')->only(['create', 'store']);
        $this->middleware('permission:edit tahun_ajaran')->only(['edit', 'update']);
        $this->middleware('permission:delete tahun_ajaran')->only('destroy');
    }

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'tahun-ajaran');

        $tahunData = TahunAjaran::with('bulan')
            ->orderBy('nama', 'desc')
            ->paginate(10, ['*'], 'ta_page')
            ->withQueryString();

        $kelasData = Kelas::with('tahunAjaran')
            ->orderBy('nama')
            ->paginate(10, ['*'], 'kelas_page')
            ->withQueryString();

        return view('akademik.index', [
            'activeTab' => $activeTab,
            'tahunData' => $tahunData,
            'kelasData' => $kelasData,
        ]);
    }

    public function create()
    {
        $bulanList = Bulan::orderBy('urutan')->get();
        return view('tahun-ajaran.form', [
            'mode' => 'create',
            'action' => route('tahun-ajaran.store'),
            'tahunA' => new TahunAjaran,
            'bulanList' => $bulanList,
            'selected' => [],
        ]);
    }

    public function store(Request $req)
    {
        $v = $req->validate([
            'nama' => 'required|unique:tahun_ajaran,nama',
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'boolean',
            'bulan' => 'required|array|min:1',
            'bulan.*' => 'exists:bulan,id',
        ]);

        if (!empty($v['aktif'])) {
            TahunAjaran::where('aktif', true)
                ->update(['aktif' => false]);
        }

        $ta = TahunAjaran::create(
            Arr::only($v, ['nama', 'semester', 'aktif'])
        );
        $ta->bulan()->sync($v['bulan']);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        $bulanList = Bulan::orderBy('urutan')->get();
        return view('tahun-ajaran.form', [
            'mode' => 'edit',
            'action' => route('tahun-ajaran.update', $tahun_ajaran),
            'tahunA' => $tahun_ajaran,
            'bulanList' => $bulanList,
            'selected' => $tahun_ajaran->bulan->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $req, TahunAjaran $tahun_ajaran)
    {
        $v = $req->validate([
            'nama' => "required|unique:tahun_ajaran,nama,{$tahun_ajaran->id}",
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'boolean',
            'bulan' => 'required|array|min:1',
            'bulan.*' => 'exists:bulan,id',
        ]);

        if (!empty($v['aktif'])) {
            TahunAjaran::where('aktif', true)
                ->update(['aktif' => false]);
        }

        $tahun_ajaran->update(
            Arr::only($v, ['nama', 'semester', 'aktif'])
        );
        $tahun_ajaran->bulan()->sync($v['bulan']);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return back()->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
