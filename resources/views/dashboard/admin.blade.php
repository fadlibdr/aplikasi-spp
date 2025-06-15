@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4">Admin Dashboard</h1>

  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">Siswa Aktif</h5>
          <h2>{{ $activeStudents }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">Pembayaran Bulan Ini</h5>
          <h2>{{ $paymentsThisMonth }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">Siswa Belum Bayar</h5>
          <h2>{{ $pendingStudents }}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
