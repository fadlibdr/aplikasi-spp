<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\Storage;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profil = ProfilSekolah::first();
        return view('profil.index', compact('profil'));
    }

    public function create()
    {
        return view('profil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'telepon' => 'required|string',
            'kepala_sekolah' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'gambar' => 'nullable|image|max:4096',
        ]);

        $profil = new ProfilSekolah();
        $profil->nama = $request->nama;
        $profil->alamat = $request->alamat;
        $profil->email = $request->email;
        $profil->telepon = $request->telepon;
        $profil->kepala_sekolah = $request->kepala_sekolah;

        if ($request->hasFile('logo')) {
            $profil->logo = $request->file('logo')->store('uploads/logo', 'public');
        }
        if ($request->hasFile('gambar')) {
            $profil->gambar = $request->file('gambar')->store('uploads/gambar', 'public');
        }

        $profil->save();

        return redirect()->route('profil.index')->with('success', 'Profil sekolah berhasil dibuat.');
    }

    public function edit(ProfilSekolah $profil)
    {
        // Cek apakah $profil ada
        // dd($profil);
        return view('profil.edit', compact('profil'));
    }




    public function update(Request $request, ProfilSekolah $profil)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'telepon' => 'required|string',
            'kepala_sekolah' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'gambar' => 'nullable|image|max:4096',
        ]);

        $profil->nama = $request->nama;
        $profil->alamat = $request->alamat;
        $profil->email = $request->email;
        $profil->telepon = $request->telepon;
        $profil->kepala_sekolah = $request->kepala_sekolah;

        if ($request->hasFile('logo')) {
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }
            $profil->logo = $request->file('logo')->store('uploads/logo', 'public');
        }
        if ($request->hasFile('gambar')) {
            if ($profil->gambar && Storage::disk('public')->exists($profil->gambar)) {
                Storage::disk('public')->delete($profil->gambar);
            }
            $profil->gambar = $request->file('gambar')->store('uploads/gambar', 'public');
        }

        $profil->save();

        return redirect()->route('profil.index')->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profil = ProfilSekolah::findOrFail($id);
        if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
            Storage::disk('public')->delete($profil->logo);
        }
        if ($profil->gambar && Storage::disk('public')->exists($profil->gambar)) {
            Storage::disk('public')->delete($profil->gambar);
        }
        $profil->delete();

        return redirect()->route('profil.index')->with('success', 'Profil sekolah berhasil dihapus.');
    }
}
