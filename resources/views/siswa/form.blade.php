@extends('layouts.app')
@section('content')
<div class="container">
  <h3>{{ $mode==='create' ? 'Tambah' : 'Edit' }} Siswa</h3>

  <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($mode==='edit')
      @method('PUT')
    @endif

    <div class="row">
      {{-- Foto --}}
      <div class="col-md-4 text-center">
        @if($siswa->foto)
          <img src="{{ asset('storage/'.$siswa->foto) }}"
               alt="Foto {{ $siswa->nama_depan }}"
               class="img-fluid rounded mb-2">
        @endif
        <div class="form-group">
          <label for="foto">Foto</label>
          <input type="file" name="foto" id="foto" class="form-control-file">
          @error('foto')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="col-md-8">
        {{-- NIS / NISN --}}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nis">NIS</label>
            <input type="text" name="nis" id="nis"
                   class="form-control"
                   value="{{ old('nis',$siswa->nis) }}" required>
            @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-6">
            <label for="nisn">NISN</label>
            <input type="text" name="nisn" id="nisn"
                   class="form-control"
                   value="{{ old('nisn',$siswa->nisn) }}">
            @error('nisn')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Nama --}}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nama_depan">Nama Depan</label>
            <input type="text" name="nama_depan" id="nama_depan"
                   class="form-control"
                   value="{{ old('nama_depan',$siswa->nama_depan) }}" required>
            @error('nama_depan')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-6">
            <label for="nama_belakang">Nama Belakang</label>
            <input type="text" name="nama_belakang" id="nama_belakang"
                   class="form-control"
                   value="{{ old('nama_belakang',$siswa->nama_belakang) }}" required>
            @error('nama_belakang')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Email & Tanggal Lahir --}}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control"
                   value="{{ old('email',$siswa->email) }}" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-6">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                   class="form-control"
                   value="{{ old('tanggal_lahir',$siswa->tanggal_lahir) }}">
            @error('tanggal_lahir')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Jenis Kelamin & Kelas --}}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
              <option value="">Pilih…</option>
              @foreach(['Laki-laki','Perempuan'] as $jk)
                <option value="{{ $jk }}"
                  {{ old('jenis_kelamin',$siswa->jenis_kelamin)===$jk ? 'selected' : '' }}>
                  {{ $jk }}
                </option>
              @endforeach
            </select>
            @error('jenis_kelamin')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-6">
            <label for="kelas_id">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
              <option value="">Pilih Kelas…</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id }}"
                  {{ old('kelas_id',$siswa->kelas_id)==$k->id ? 'selected' : '' }}>
                  {{ $k->nama }} ({{ $k->tahunAjaran->nama }})
                </option>
              @endforeach
            </select>
            @error('kelas_id')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Alamat --}}
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ old('alamat',$siswa->alamat) }}</textarea>
          @error('alamat')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Wali Murid & Kontak --}}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="wali_murid">Wali Murid</label>
            <input type="text" name="wali_murid" id="wali_murid"
                   class="form-control"
                   value="{{ old('wali_murid',$siswa->wali_murid) }}">
            @error('wali_murid')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-6">
            <label for="kontak_wali_murid">Kontak Wali Murid</label>
            <input type="text" name="kontak_wali_murid" id="kontak_wali_murid"
                   class="form-control"
                   value="{{ old('kontak_wali_murid',$siswa->kontak_wali_murid) }}">
            @error('kontak_wali_murid')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Tanggal Masuk & Status --}}
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="tanggal_awal_masuk">Tanggal Masuk</label>
            <input type="date" name="tanggal_awal_masuk" id="tanggal_awal_masuk"
                   class="form-control"
                   value="{{ old('tanggal_awal_masuk',$siswa->tanggal_awal_masuk) }}">
            @error('tanggal_awal_masuk')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-4">
            <label for="status_siswa">Status Siswa</label>
            <select name="status_siswa" id="status_siswa" class="form-control" required>
              @foreach(['aktif','nonaktif','lulus'] as $st)
                <option value="{{ $st }}"
                  {{ old('status_siswa',$siswa->status_siswa)===$st ? 'selected' : '' }}>
                  {{ ucfirst($st) }}
                </option>
              @endforeach
            </select>
            @error('status_siswa')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="form-group col-md-4">
            <label for="status_awal_siswa">Status Awal</label>
            <input type="text" name="status_awal_siswa" id="status_awal_siswa"
                   class="form-control"
                   value="{{ old('status_awal_siswa',$siswa->status_awal_siswa) }}">
            @error('status_awal_siswa')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
        </div>

        {{-- Status Akhir --}}
        <div class="form-group">
          <label for="status_akhir_siswa">Status Akhir</label>
          <input type="text" name="status_akhir_siswa" id="status_akhir_siswa"
                 class="form-control"
                 value="{{ old('status_akhir_siswa',$siswa->status_akhir_siswa) }}">
          @error('status_akhir_siswa')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

      </div>
    </div>

    <button type="submit" class="btn btn-success">
      {{ $mode==='create' ? 'Simpan' : 'Perbarui' }}
    </button>
    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
