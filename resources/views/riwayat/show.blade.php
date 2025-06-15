<!-- resources/views/riwayat/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Detail Transaksi</h4>
    <ul class="list-group">
        <li class="list-group-item"><strong>Order ID:</strong> {{ $data->order_id }}</li>
        <li class="list-group-item"><strong>Siswa:</strong> {{ $data->iuran->siswa->nama_depan }} {{ $data->iuran->siswa->nama_belakang }}</li>
        <li class="list-group-item"><strong>Jenis Pembayaran:</strong> {{ $data->iuran->jenisPembayaran->nama }}</li>
        <li class="list-group-item"><strong>Jumlah:</strong> Rp{{ number_format($data->jumlah, 0, ',', '.') }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($data->status) }}</li>
        <li class="list-group-item"><strong>Tanggal Bayar:</strong> {{ $data->tgl_bayar }}</li>
        <li class="list-group-item"><strong>Midtrans ID:</strong> {{ $data->midtrans_id }}</li>
    </ul>
    <a href="{{ route('riwayat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
