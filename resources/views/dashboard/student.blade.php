@extends('layouts.app')

@section('content')
  <div class="container">
    <h1 class="mb-4">Dashboard Siswa</h1>

    <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Profil</h5>
      <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
      <p><strong>Nama:</strong> {{ $siswa->nama_depan }} {{ $siswa->nama_belakang }}</p>
      <p><strong>Kelas:</strong> {{ $siswa->kelas->nama }}</p>
    </div>
    </div>

    <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-white bg-warning shadow-sm">
      <div class="card-body text-center">
        <h5 class="card-title">Total Tagihan Pending</h5>
        <h2>Rp {{ number_format($totalPending, 0, ',', '.') }}</h2>
      </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-white bg-success shadow-sm">
      <div class="card-body text-center">
        <h5 class="card-title">Jumlah Tagihan</h5>
        <h2>{{ $pendingIurans->count() }}</h2>
      </div>
      </div>
    </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Daftar Tagihan</h5>
      <table class="table table-striped">
      <thead>
        <tr>
        <th>#</th>
        <th>Jenis</th>
        <th>Nominal</th>
        <th>Bulan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pendingIurans as $idx => $iuran)
      <tr>
      <td>{{ $idx + 1 }}</td>
      <td>{{ $iuran->jenisPembayaran->nama }}</td>
      <td>Rp {{ number_format($iuran->jenisPembayaran->nominal, 0, ',', '.') }}</td>
      <td>{{ $iuran->bulan }}</td>
      </tr>
      @endforeach
      </tbody>
      </table>
    </div>
    </div>
  </div>
@endsection