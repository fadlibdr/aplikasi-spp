@extends('layouts.app')
@section('content')
<div class="container">
  <h3>{{ $mode==='create' ? 'Tambah' : 'Edit' }} Tahun Ajaran</h3>

  <form action="{{ $action }}" method="POST">
    @csrf
    @if($mode==='edit')
      @method('PUT')
    @endif

    {{-- Nama --}}
    <div class="form-group">
      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" class="form-control"
             value="{{ old('nama',$tahunA->nama) }}" required>
      @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Semester --}}
    <div class="form-group">
      <label for="semester">Semester</label>
      <select name="semester" id="semester" class="form-control" required>
        <option value="">Pilih…</option>
        @foreach(['Ganjil','Genap'] as $s)
          <option value="{{ $s }}"
            {{ old('semester',$tahunA->semester)===$s ? 'selected' : '' }}>
            {{ $s }}
          </option>
        @endforeach
      </select>
      @error('semester')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Bulan Aktif --}}
    <div class="form-group">
      <label>Bulan Aktif</label>
      <div class="row">
        @foreach($bulanList as $b)
          <div class="col-md-3">
            <div class="form-check">
              <input type="checkbox"
                     name="bulan[]"
                     value="{{ $b->id }}"
                     id="b{{ $b->id }}"
                     class="form-check-input"
                     {{ in_array($b->id, old('bulan',$selected)) ? 'checked' : '' }}>
              <label for="b{{ $b->id }}" class="form-check-label">
                {{ $b->nama }}
              </label>
            </div>
          </div>
        @endforeach
      </div>
      @error('bulan')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    {{-- Aktif Checkbox --}}
    <div class="form-group form-check">
      <input type="checkbox" name="aktif" id="aktif"
             class="form-check-input"
             {{ old('aktif',$tahunA->aktif) ? 'checked' : '' }}>
      <label for="aktif" class="form-check-label">
        Set sebagai Tahun Ajaran Aktif
      </label>
    </div>

    {{-- Buttons --}}
    <button type="submit" class="btn btn-success">
      {{ $mode==='create' ? 'Simpan' : 'Perbarui' }}
    </button>
    <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
