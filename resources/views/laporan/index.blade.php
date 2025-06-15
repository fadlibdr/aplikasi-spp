@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Pilih Laporan & Rekapitulasi</h3>

        <form action="{{ route('laporan.generate') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Jenis Laporan</label>
                    <select name="type" class="form-control" required>
                        <option value="">— Pilih —</option>
                        @foreach($reportTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Dari</label>
                    <input type="date" name="date_from" class="form-control" value="{{ old('date_from') }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Sampai</label>
                    <input type="date" name="date_to" class="form-control" value="{{ old('date_to') }}" required>
                </div>
                <div class="form-group col-md-2 align-self-end">
                    <button class="btn btn-primary btn-block">Generate</button>
                </div>
            </div>
        </form>
    </div>
@endsection