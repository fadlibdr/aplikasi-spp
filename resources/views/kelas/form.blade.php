@extends('layouts.app')
@section('content')
<div class="container">
  <h3>{{ $mode==='create' ? 'Tambah' : 'Edit' }} Kelas</h3>

  <form action="{{ $action }}" method="POST">
    @csrf
    @if($mode==='edit')
      @method('PUT')
    @endif

    {{-- Nama --}}
    <div class="form-group">
      <label for="nama">Nama Kelas</label>
      <input type="text" name="nama" id="nama"
             class="form-control"
             value="{{ old('nama',$kelas->nama) }}"
             required maxlength="50">
      @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Kapasitas --}}
    <div class="form-group">
      <label for="kapasitas">Kapasitas</label>
      <input type="number" name="kapasitas" id="kapasitas"
             class="form-control"
             value="{{ old('kapasitas',$kelas->kapasitas) }}"
             required min="0">
      @error('kapasitas')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Tahun Ajaran --}}
    <div class="form-group">
      <label for="tahun_ajaran_id">Tahun Ajaran</label>
      <select name="tahun_ajaran_id" id="tahun_ajaran_id"
              class="form-control" required>
        <option value="">Pilih Tahun Ajaran…</option>
        @foreach($tahunList as $ta)
          <option value="{{ $ta->id }}"
            {{ old('tahun_ajaran_id',$kelas->tahun_ajaran_id)==$ta->id ? 'selected' : '' }}>
            {{ $ta->nama }} ({{ $ta->semester }})
          </option>
        @endforeach
      </select>
      @error('tahun_ajaran_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Buttons --}}
    <button type="submit" class="btn btn-success">
      {{ $mode==='create' ? 'Simpan' : 'Perbarui' }}
    </button>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
