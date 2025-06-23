@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Profil Siswa</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <h5 class="mt-3">Data Siswa</h5>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nama_depan">Nama Depan</label>
                <input type="text" id="nama_depan" name="nama_depan" class="form-control" value="{{ old('nama_depan', $siswa->nama_depan) }}">
                @error('nama_depan')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="nama_belakang">Nama Belakang</label>
                <input type="text" id="nama_belakang" name="nama_belakang" class="form-control" value="{{ old('nama_belakang', $siswa->nama_belakang) }}">
                @error('nama_belakang')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                    <option value="">Pilih…</option>
                    @foreach(['Laki-laki','Perempuan'] as $jk)
                        <option value="{{ $jk }}" {{ old('jenis_kelamin',$siswa->jenis_kelamin)===$jk ? 'selected' : '' }}>{{ $jk }}</option>
                    @endforeach
                </select>
                @error('jenis_kelamin')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="tempat_lahir">Tempat Lahir</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                @error('tempat_lahir')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                @error('tanggal_lahir')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="no_hp">No. HP</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp) }}">
                @error('no_hp')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
            @error('alamat')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $siswa->email) }}">
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <h5 class="mt-4">Data Sekolah</h5>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nis">NIS</label>
                <input type="text" id="nis" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}">
                @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="nisn">NISN</label>
                <input type="text" id="nisn" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
                @error('nisn')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id',$siswa->kelas_id)==$k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->tahunAjaran->nama }})</option>
                    @endforeach
                </select>
                @error('kelas_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control">
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>

        <h5 class="mt-4">Data Keluarga</h5>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nama_ibu">Nama Ibu</label>
                <input type="text" id="nama_ibu" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                @error('nama_ibu')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group col-md-6">
                <label for="nama_ayah">Nama Ayah</label>
                <input type="text" id="nama_ayah" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                @error('nama_ayah')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-group">
            <label for="kontak_wali_murid">No. HP Wali</label>
            <input type="text" id="kontak_wali_murid" name="kontak_wali_murid" class="form-control" value="{{ old('kontak_wali_murid', $siswa->kontak_wali_murid) }}">
            @error('kontak_wali_murid')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
