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

    <div class="row mt-4">
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Pembayaran</h6>
          </div>
          <div class="card-body">
            <div class="chart-pie pt-4">
              <canvas id="paymentChart"></canvas>
            </div>
            <hr>
            Total terbayar: {{ $totalReceived }} <br>
            Total pending: {{  $totalPending }}
          </div>
        </div>
      </div>
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kalender Akademik</h6>
          </div>
          <div class="card-body">
            <div id="academicCalendar"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mt-4">
    <div class="card-body">
      <h5 class="card-title">Status Pembayaran Siswa</h5>
      <table class="table table-bordered">
      <thead>
        <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($studentPayments as $s)
      <tr>
      <td>{{ $s->nis }}</td>
      <td>{{ $s->nama_depan }} {{ $s->nama_belakang }}</td>
      <td>
        <span class="badge badge-{{ $s->pending_iuran_count ? 'warning' : 'success' }}">
        {{ $s->pending_iuran_count ? 'Belum Lunas' : 'Lunas' }}
        </span>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
    </div>
    </div>
  </div>
@endsection

  @push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
  @endpush

  @push('scripts')
  <script src="{{ asset('vendor/sb-admin-2/vendor/chart.js/Chart.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('paymentChart'), {
      type: 'doughnut',
      data: {
      labels: ['Diterima', 'Pending'],
      datasets: [{
        data: [{{ $totalReceived }}, {{ $totalPending }}],
        backgroundColor: ['#1cc88a', '#f6c23e'],
        hoverBackgroundColor: ['#17a673', '#f4b619'],
        borderWidth: 1
      }]
      },
      options: {
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
      }
    });

    const cal = new FullCalendar.Calendar(document.getElementById('academicCalendar'), {
      initialView: 'dayGridMonth',
      height: 350,
      events: {!! $calendarEvents !!}
    });
    cal.render();
    });
  </script>
  @endpush
