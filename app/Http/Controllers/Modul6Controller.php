<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class Modul6Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // hanya admin/operator yang boleh manage
        $this->middleware('permission:create jenis_pembayaran')->only(['createJenis', 'storeJenis']);
        $this->middleware('permission:edit jenis_pembayaran')->only(['editJenis', 'updateJenis']);
        $this->middleware('permission:delete jenis_pembayaran')->only('destroyJenis');
        $this->middleware('permission:create iuran')->only(['createIuran', 'storeIuran']);
        $this->middleware('permission:edit iuran')->only(['editIuran', 'updateIuran']);
        $this->middleware('permission:delete iuran')->only('destroyIuran');
    }

    //
    // ===== Jenis Pembayaran =====
    //


    public function indexJenis()
    {
        $jenisList = JenisPembayaran::orderBy('kode')->get();
        $iuranList = Iuran::with(['siswa', 'jenisPembayaran'])
            ->latest()
            ->limit(5)
            ->get();

        return view('modul6.index', [
            'jenisList' => $jenisList,
            'iuranList' => $iuranList,
            'active' => 'jenis',    // di sini assign secara manual
        ]);
    }

    public function createJenis()
    {
        return view('modul6.form', [
            'type' => 'jenis',
            'action' => route('jenis.store'),
            'data' => new JenisPembayaran,
        ]);
    }

    public function storeJenis(Request $request)
    {
        $v = $request->validate([
            'kode' => 'required|unique:jenis_pembayaran,kode',
            'nama' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'frekuensi' => 'required|in:Bulanan,Tahunan',
        ]);
        JenisPembayaran::create($v);
        return redirect()->route('jenis.index')
            ->with('success', 'Jenis Pembayaran tersimpan.');
    }

    public function editJenis(JenisPembayaran $jenis)
    {
        return view('modul6.form', [
            'type' => 'jenis',
            'action' => route('jenis.update', $jenis),
            'data' => $jenis,
        ]);
    }

    public function updateJenis(Request $request, JenisPembayaran $jenis)
    {
        $v = $request->validate([
            'kode' => "required|unique:jenis_pembayaran,kode,{$jenis->id}",
            'nama' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'frekuensi' => 'required|in:Bulanan,Tahunan',
        ]);
        $jenis->update($v);
        return redirect()->route('jenis.index')
            ->with('success', 'Jenis Pembayaran diperbarui.');
    }

    public function destroyJenis(JenisPembayaran $jenis)
    {
        $jenis->delete();
        return back()->with('success', 'Jenis Pembayaran dihapus.');
    }

    //
    // ===== Iuran =====
    //

    public function indexIuran()
    {
        $iuranList = Iuran::with(['siswa', 'jenisPembayaran'])
            ->orderBy('bulan')
            ->paginate(15);

        return view('modul6.index', [
            'iuranList' => $iuranList,
            'active' => 'iuran',
        ]);
    }
    public function createIuran()
    {
        $siswaList = Siswa::orderBy('nama_depan')->get();
        $jenisList = JenisPembayaran::orderBy('nama')->get();
        return view('modul6.form', [
            'type' => 'iuran',
            'action' => route('iuran.store'),
            'data' => new Iuran,
            'siswaList' => $siswaList,
            'jenisList' => $jenisList,
        ]);
    }

    public function storeIuran(Request $request)
    {
        $v = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'bulan' => 'required|integer|min:1|max:12',
        ]);
        Iuran::create($v);
        return redirect()->route('iuran.index')
            ->with('success', 'Iuran berhasil dibuat.');
    }

    public function editIuran(Iuran $iuran)
    {
        $siswaList = Siswa::orderBy('nama_depan')->get();
        $jenisList = JenisPembayaran::orderBy('nama')->get();
        return view('modul6.form', [
            'type' => 'iuran',
            'action' => route('iuran.update', $iuran),
            'data' => $iuran,
            'siswaList' => $siswaList,
            'jenisList' => $jenisList,
        ]);
    }

    public function updateIuran(Request $request, Iuran $iuran)
    {
        $v = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'bulan' => 'required|integer|min:1|max:12',
        ]);
        $iuran->update($v);
        return redirect()->route('iuran.index')
            ->with('success', 'Iuran diperbarui.');
    }

    public function destroyIuran(Iuran $iuran)
    {
        $iuran->delete();
        return back()->with('success', 'Iuran dihapus.');
    }
}
