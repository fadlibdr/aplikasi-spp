@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Tambah Entri Jurnal Umum</h3>

        <form action="{{ route('jurnal-umum.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $entry->tanggal) }}"
                    required>
                @if($errors->has('tanggal'))
                    <small class="text-danger">{{ $errors->first('tanggal') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"
                    required>{{ old('keterangan', $entry->keterangan) }}</textarea>
                @if($errors->has('keterangan'))
                    <small class="text-danger">{{ $errors->first('keterangan') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label>Debit</label>
                <input type="number" name="debit" step="0.01" class="form-control" value="{{ old('debit', $entry->debit) }}"
                    required>
                @if($errors->has('debit'))
                    <small class="text-danger">{{ $errors->first('debit') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label>Kredit</label>
                <input type="number" name="kredit" step="0.01" class="form-control"
                    value="{{ old('kredit', $entry->kredit) }}" required>
                @if($errors->has('kredit'))
                    <small class="text-danger">{{ $errors->first('kredit') }}</small>
                @endif
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('jurnal-umum.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection