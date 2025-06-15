<!-- resources/views/riwayat/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Riwayat Pembayaran</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Jenis Pembayaran</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $item)
                <tr>
                    <td>{{ $item->tgl_bayar }}</td>
                    <td>{{ $item->iuran->siswa->nama_depan }} {{ $item->iuran->siswa->nama_belakang }}</td>
                    <td>{{ $item->iuran->jenisPembayaran->nama }}</td>
                    <td>Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $item->status == 'settlement' ? 'success' : 'warning' }}">{{ ucfirst($item->status) }}</span></td>
                    <td><a href="{{ route('riwayat.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $pembayaran->links() }}
</div>
@endsection
