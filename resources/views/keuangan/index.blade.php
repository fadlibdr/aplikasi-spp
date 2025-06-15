@extends('layouts.app')

@section('content')
<h4>Riwayat Keuangan</h4>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('keuangan.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>

<h5>Penerimaan</h5>
<table class="table table-striped">
    <thead><tr><th>Tanggal</th><th>Sumber</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
    <tbody>
        @foreach($penerimaan as $p)
        <tr>
            <td>{{ $p->tanggal }}</td>
            <td>
                {{ $p->sumber }}
                @if($p->pembayaran_id)
                    (Iuran: {{ $p->pembayaran->order_id ?? 'N/A' }})
                @endif
            </td>
            <td>Rp{{ number_format($p->jumlah,0,',','.') }}</td>
            <td>{{ $p->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h5>Pengeluaran</h5>
<table class="table table-striped">
    <thead><tr><th>Tanggal</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
    <tbody>
        @foreach($pengeluaran as $p)
        <tr>
            <td>{{ $p->tanggal }}</td>
            <td>{{ $p->kategori }}</td>
            <td>Rp{{ number_format($p->jumlah,0,',','.') }}</td>
            <td>{{ $p->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('keuangan.export-excel') }}" class="btn btn-success mb-2">Export Excel</a>

<a href="{{ route('keuangan.export-pdf') }}" class="btn btn-danger mb-2">Cetak PDF</a>


@endsection
