@extends('layouts.app')

@section('content')

<div class="container">
  <h3>Daftar Iuran Pending</h3>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Siswa</th>
        <th>Jenis Pembayaran</th>
        <th>Nominal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($iurans as $i)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $i->siswa->nama_depan }} {{ $i->siswa->nama_belakang }}</td>
      <td>{{ $i->jenisPembayaran->nama }}</td>
      <td>Rp {{ number_format($i->jenisPembayaran->nominal, 0, ',', '.') }}</td>
      <td>
        <a href="{{ route('pembayaran.bayar', $i->id) }}" class="btn btn-sm btn-primary">
        Bayar
        </a>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="5" class="text-center">Tidak ada iuran pending.</td>
      </tr>
    @endforelse
    </tbody>
  </table>
</div>

@endsections