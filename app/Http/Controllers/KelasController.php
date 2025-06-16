<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view kelas')->only('index');
        $this->middleware('permission:create kelas')->only(['create','store']);
        $this->middleware('permission:edit kelas')->only(['edit','update']);
        $this->middleware('permission:delete kelas')->only('destroy');
    }

    public function index()
    {
        return redirect()->route('tahun-ajaran.index', ['tab' => 'kelas']);
    }

    public function create()
    {
        $tahunList = TahunAjaran::where('aktif', true)
                               ->orderBy('nama','desc')
                               ->get();

        return view('kelas.form', [
            'mode'      => 'create',
            'action'    => route('kelas.store'),
            'kelas'     => new Kelas,
            'tahunList' => $tahunList,
        ]);
    }

    public function store(Request $req)
    {
        $v = $req->validate([
            'nama'             => 'required|string|max:50',
            'kapasitas'        => 'required|integer|min:0',
            'tahun_ajaran_id'  => 'required|exists:tahun_ajaran,id',
        ]);

        Kelas::create($v);

        return redirect()->route('kelas.index')
                         ->with('success','Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $tahunList = TahunAjaran::where('aktif', true)
                               ->orderBy('nama','desc')
                               ->get();

        return view('kelas.form', [
            'mode'      => 'edit',
            'action'    => route('kelas.update', $kelas),
            'kelas'     => $kelas,
            'tahunList' => $tahunList,
        ]);
    }

    public function update(Request $req, Kelas $kelas)
    {
        $v = $req->validate([
            'nama'             => "required|string|max:50|unique:kelas,nama,{$kelas->id},id,tahun_ajaran_id,{$req->tahun_ajaran_id}",
            'kapasitas'        => 'required|integer|min:0',
            'tahun_ajaran_id'  => 'required|exists:tahun_ajaran,id',
        ]);

        $kelas->update($v);

        return redirect()->route('kelas.index')
                         ->with('success','Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success','Kelas berhasil dihapus.');
    }
}
