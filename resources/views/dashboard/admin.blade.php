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

  <script src="{{ asset('vendor/sb-admin-2/vendor/chart.js/Chart.min.js') }}"></script>

  <canvas id="paymentChart"></canvas>

  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Status Pembayaran Siswa</h5>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Lunas</th>
            <th>Pending</th>
          </tr>
        </thead>
        <tbody>
          @foreach($studentsStatus as $s)
            <tr>
              <td>{{ $s->nama_depan }} {{ $s->nama_belakang }}</td>
              <td>{{ $s->kelas->nama }}</td>
              <td>{{ $s->paid_count }}</td>
              <td>{{ $s->pending_count }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@push('scripts')
<script>
    const ctx = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Diterima', 'Pending'],
            datasets: [{
                data: [{{ $totalReceived }}, {{ $totalPending }}],
                backgroundColor: ['#1cc88a', '#f6c23e'],
            }]
        }
    });
</script>
@endpush

@endsection
