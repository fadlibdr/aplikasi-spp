@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Jurnal Umum</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('jurnal-umum.create') }}" class="btn btn-primary">
                Tambah Entri
            </a>
            <a href="{{ route('jurnal-umum.export-excel') }}" class="btn btn-success">
                Export Excel
            </a>
            <a href="{{ route('jurnal-umum.cetak-pdf') }}" class="btn btn-danger">
                Cetak PDF
            </a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $e)
                    <tr>
                        <td>{{ $e->tanggal }}</td>
                        <td>{{ $e->keterangan }}</td>
                        <td>Rp {{ number_format($e->debit, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($e->kredit, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection