<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Kelas;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $siswa = Siswa::where('user_id', Auth::id())
            ->with('kelas.tahunAjaran')
            ->firstOrFail();
        $kelasList = Kelas::orderBy('nama')->get();
        return view('student.profile', compact('siswa', 'kelasList'));
    }

    public function update(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $v = $request->validate([
            'nama_depan' => 'required|string|max:50',
            'nama_belakang' => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email' => "required|email|unique:siswa,email,{$siswa->id}",
            'nis' => "required|unique:siswa,nis,{$siswa->id}",
            'nisn' => "nullable|unique:siswa,nisn,{$siswa->id}",
            'kelas_id' => 'required|exists:kelas,id',
            'password' => 'nullable|confirmed|min:8',
            'nama_ibu' => 'nullable|string|max:100',
            'nama_ayah' => 'nullable|string|max:100',
            'kontak_wali_murid' => 'nullable|string|max:20',
        ]);

        $siswa->update($v);

        if ($siswa->user) {
            $siswa->user->update([
                'name' => $v['nama_depan'].' '.$v['nama_belakang'],
                'email' => $v['email'],
            ]);
            if ($request->filled('password')) {
                $siswa->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        return redirect()->route('student.profile.edit')->with('success', 'Profil diperbarui.');
    }
}
