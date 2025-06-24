<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view siswa')->only(['index', 'ref']);
        $this->middleware('permission:create siswa')->only(['create', 'store', 'import']);
        $this->middleware('permission:edit siswa')->only(['edit', 'update', 'naikKelas', 'pindahSekolah', 'lulus', 'applyRef']);
        $this->middleware('permission:delete siswa')->only('destroy');
    }

    public function index()
    {
        $data = Siswa::with('kelas')->orderBy('nis')->paginate(15);
        $kelasList = Kelas::orderBy('nama')->get();
        return view('siswa.index', compact('data', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        return view('siswa.form', [
            'mode' => 'create',
            'action' => route('siswa.store'),
            'siswa' => new Siswa,
            'kelasList' => $kelasList,
        ]);
    }


    public function store(Request $req)
    {
        $v = $req->validate([
            'nis' => 'required|unique:siswa,nis',
            'nisn' => 'nullable|unique:siswa,nisn',
            'nama_depan' => 'required|string|max:50',
            'nama_belakang' => 'required|string|max:50',
            'foto' => 'nullable|image|max:2048',
            'email' => 'required|email|unique:siswa,email',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'wali_murid' => 'nullable|string|max:100',
            'kontak_wali_murid' => 'nullable|string|max:20',
            'tanggal_awal_masuk' => 'nullable|date',
            'status_siswa' => 'required|in:aktif,nonaktif,lulus',
            'status_awal_siswa' => 'nullable|string',
            'status_akhir_siswa' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        if ($req->hasFile('foto')) {
            $path = $req->file('foto')->store('foto_siswa', 'public');
            $v['foto'] = $path;
        }

        $birth = isset($v['tanggal_lahir']) ? Carbon::parse($v['tanggal_lahir'])->format('dmy') : '';
        $user = User::create([
            'name' => $v['nama_depan'] . ' ' . $v['nama_belakang'],
            'email' => $v['email'],
            'password' => Hash::make($v['nama_belakang'] . $birth),
        ]);
        $user->assignRole('siswa');

        $v['user_id'] = $user->id;

        Siswa::create($v);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        return view('siswa.form', [
            'mode' => 'edit',
            'action' => route('siswa.update', $siswa),
            'siswa' => $siswa,
            'kelasList' => $kelasList,
        ]);
    }

    public function update(Request $req, Siswa $siswa)
    {
        $v = $req->validate([
            'nis' => "required|unique:siswa,nis,{$siswa->id}",
            'nisn' => "nullable|unique:siswa,nisn,{$siswa->id}",
            'nama_depan' => 'required|string|max:50',
            'nama_belakang' => 'required|string|max:50',
            'foto' => 'nullable|image|max:2048',
            'email' => "required|email|unique:siswa,email,{$siswa->id}",
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'wali_murid' => 'nullable|string|max:100',
            'kontak_wali_murid' => 'nullable|string|max:20',
            'tanggal_awal_masuk' => 'nullable|date',
            'status_siswa' => 'required|in:aktif,nonaktif,lulus',
            'status_awal_siswa' => 'nullable|string',
            'status_akhir_siswa' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        if ($req->hasFile('foto')) {
            // hapus file lama jika ada
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $v['foto'] = $req->file('foto')->store('foto_siswa', 'public');
        }

        $siswa->update($v);

        if ($siswa->user) {
            $siswa->user->update([
                'name' => $v['nama_depan'] . ' ' . $v['nama_belakang'],
                'email' => $v['email'],
            ]);
        }

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }
        $siswa->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function import(Request $req)
    {
        $req->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new SiswaImport, $req->file('file'));
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diimpor.');
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/template_import_siswa.csv');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download(
            $path,
            'template_import_siswa.csv',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    public function ref(Request $req)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $selectedKelas = $req->query('kelas');
        $query = Siswa::with('kelas')->orderBy('nis');
        if ($selectedKelas) {
            $query->where('kelas_id', $selectedKelas);
        }
        $siswaList = $query->paginate(20);

        return view('siswa.ref', compact('kelasList', 'siswaList', 'selectedKelas'));
    }

    public function applyRef(Request $req)
    {
        $v = $req->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswa,id',
            'action' => 'required|in:naik,pindah,lulus',
            'kelas_id' => 'required_if:action,naik|exists:kelas,id',
        ]);

        $ids = $v['siswa_ids'];

        switch ($v['action']) {
            case 'naik':
                Siswa::whereIn('id', $ids)->update(['kelas_id' => $v['kelas_id']]);
                break;
            case 'pindah':
                Siswa::whereIn('id', $ids)->update([
                    'status_siswa' => 'nonaktif',
                    'status_akhir_siswa' => 'pindah',
                ]);
                break;
            case 'lulus':
                Siswa::whereIn('id', $ids)->update([
                    'status_siswa' => 'lulus',
                    'status_akhir_siswa' => 'lulus',
                ]);
                break;
        }

        return back()->with('success', 'Status siswa berhasil diperbarui.');
    }

    public function naikKelas(Request $req, Siswa $siswa)
    {
        $v = $req->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update(['kelas_id' => $v['kelas_id']]);

        return back()->with('success', 'Siswa berhasil naik kelas.');
    }

    public function pindahSekolah(Siswa $siswa)
    {
        $siswa->update([
            'status_siswa' => 'nonaktif',
            'status_akhir_siswa' => 'pindah',
        ]);

        return back()->with('success', 'Status siswa diperbarui menjadi pindah.');
    }

    public function lulus(Siswa $siswa)
    {
        $siswa->update([
            'status_siswa' => 'lulus',
            'status_akhir_siswa' => 'lulus',
        ]);

        return back()->with('success', 'Status siswa diperbarui menjadi lulus.');
    }
}
