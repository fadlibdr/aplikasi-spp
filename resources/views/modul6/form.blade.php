@extends('layouts.app')
@section('content')
<div class="container">
    @if($type==='jenis')
    <h3>{{ $data->exists ? 'Edit' : 'Tambah' }} Jenis Pembayaran</h3>
    @else
    <h3>{{ $data->exists ? 'Edit' : 'Tambah' }} Iuran</h3>
    @endif

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($data->exists) @method('PUT') @endif

        @if($type==='jenis')
        {{-- Kode --}}
        <div class="form-group">
            <label>Kode</label>
            <input name="kode" class="form-control" value="{{ old('kode',$data->kode) }}" required>
            @error('kode')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        {{-- Nama --}}
        <div class="form-group">
            <label>Nama</label>
            <input name="nama" class="form-control" value="{{ old('nama',$data->nama) }}" required>
            @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        {{-- Nominal --}}
        <div class="form-group">
            <label>Nominal</label>
            <input name="nominal" type="number" class="form-control" value="{{ old('nominal',$data->nominal) }}"
                required>
            @error('nominal')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        {{-- Frekuensi --}}
        <div class="form-group">
            <label>Frekuensi</label>
            <select name="frekuensi" class="form-control" required>
                <option value="">— Pilih —</option>
                @foreach(['Bulanan','Tahunan'] as $f)
                <option value="{{ $f }}" {{ old('frekuensi',$data->frekuensi)===$f ? 'selected':'' }}>
                    {{ $f }}
                </option>
                @endforeach
            </select>
            @error('frekuensi')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        @else {{-- form iuran --}}
        {{-- Siswa --}}
        <div class="form-group">
            <label>Siswa</label>
            <select name="siswa_id" class="form-control" required>
                <option value="">— Pilih —</option>
                @foreach($siswaList as $s)
                <option value="{{ $s->id }}" {{ old('siswa_id',$data->siswa_id)===$s->id?'selected':'' }}>
                    {{ $s->nama_depan }} {{ $s->nama_belakang }}
                </option>
                @endforeach
            </select>
            @error('siswa_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        {{-- Jenis Pembayaran --}}
        <div class="form-group">
            <label>Jenis Pembayaran</label>
            <select name="jenis_pembayaran_id" class="form-control" required>
                <option value="">— Pilih —</option>
                @foreach($jenisList as $j)
                <option value="{{ $j->id }}" {{ old('jenis_pembayaran_id',$data->
                    jenis_pembayaran_id)===$j->id?'selected':'' }}>
                    {{ $j->nama }}
                </option>
                @endforeach
            </select>
            @error('jenis_pembayaran_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        {{-- Bulan --}}
        <div class="form-group">
            <label>Bulan (1–12)</label>
            <input name="bulan" type="number" min="1" max="12" class="form-control"
                value="{{ old('bulan',$data->bulan) }}" required>
            @error('bulan')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        @endif

        <button class="btn btn-success">
            {{ $data->exists ? 'Perbarui' : 'Simpan' }}
        </button>
        <a href="{{ $type==='jenis'?route('jenis.index'):route('iuran.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection