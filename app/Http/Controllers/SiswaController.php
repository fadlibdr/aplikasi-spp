<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view siswa')->only('index');
        $this->middleware('permission:create siswa')->only(['create','store','import']);
        $this->middleware('permission:edit siswa')->only(['edit','update']);
        $this->middleware('permission:delete siswa')->only('destroy');
    }

    public function index()
    {
        $data = Siswa::with('kelas')->orderBy('nis')->paginate(15);
        return view('siswa.index', compact('data'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        return view('siswa.form', [
            'mode'      => 'create',
            'action'    => route('siswa.store'),
            'siswa'     => new Siswa,
            'kelasList' => $kelasList,
        ]);
    }

    public function store(Request $req)
    {
        $v = $req->validate([
            'nis'                => 'required|unique:siswa,nis',
            'nisn'               => 'nullable|unique:siswa,nisn',
            'nama_depan'         => 'required|string|max:50',
            'nama_belakang'      => 'required|string|max:50',
            'foto'               => 'nullable|image|max:2048',
            'email'              => 'required|email|unique:siswa,email',
            'tanggal_lahir'      => 'nullable|date',
            'jenis_kelamin'      => 'nullable|in:Laki-laki,Perempuan',
            'alamat'             => 'nullable|string',
            'wali_murid'         => 'nullable|string|max:100',
            'kontak_wali_murid'  => 'nullable|string|max:20',
            'tanggal_awal_masuk' => 'nullable|date',
            'status_siswa'       => 'required|in:aktif,nonaktif,lulus',
            'status_awal_siswa'  => 'nullable|string',
            'status_akhir_siswa' => 'nullable|string',
            'kelas_id'           => 'required|exists:kelas,id',
        ]);

        if ($req->hasFile('foto')) {
            $path = $req->file('foto')->store('foto_siswa','public');
            $v['foto'] = $path;
        }

        Siswa::create($v);

        return redirect()->route('siswa.index')
                         ->with('success','Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        return view('siswa.form', [
            'mode'      => 'edit',
            'action'    => route('siswa.update', $siswa),
            'siswa'     => $siswa,
            'kelasList' => $kelasList,
        ]);
    }

    public function update(Request $req, Siswa $siswa)
    {
        $v = $req->validate([
            'nis'                => "required|unique:siswa,nis,{$siswa->id}",
            'nisn'               => "nullable|unique:siswa,nisn,{$siswa->id}",
            'nama_depan'         => 'required|string|max:50',
            'nama_belakang'      => 'required|string|max:50',
            'foto'               => 'nullable|image|max:2048',
            'email'              => "required|email|unique:siswa,email,{$siswa->id}",
            'tanggal_lahir'      => 'nullable|date',
            'jenis_kelamin'      => 'nullable|in:Laki-laki,Perempuan',
            'alamat'             => 'nullable|string',
            'wali_murid'         => 'nullable|string|max:100',
            'kontak_wali_murid'  => 'nullable|string|max:20',
            'tanggal_awal_masuk' => 'nullable|date',
            'status_siswa'       => 'required|in:aktif,nonaktif,lulus',
            'status_awal_siswa'  => 'nullable|string',
            'status_akhir_siswa' => 'nullable|string',
            'kelas_id'           => 'required|exists:kelas,id',
        ]);

        if ($req->hasFile('foto')) {
            // hapus file lama jika ada
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $v['foto'] = $req->file('foto')->store('foto_siswa','public');
        }

        $siswa->update($v);

        return redirect()->route('siswa.index')
                         ->with('success','Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }
        $siswa->delete();
        return back()->with('success','Siswa berhasil dihapus.');
    }

    public function import(Request $req)
    {
        $req->validate(['file'=>'required|mimes:xlsx,xls']);
        Excel::import(new SiswaImport, $req->file('file'));
        return redirect()->route('siswa.index')
                         ->with('success','Data siswa berhasil diimpor.');
    }
}
