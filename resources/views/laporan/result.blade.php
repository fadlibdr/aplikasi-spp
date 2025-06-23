@extends('layouts.app')

@section('content')
<div class="container">
  <h3>
    @switch($type)
      @case('spp') Pembayaran SPP @break
      @case('keuangan') Keuangan @break
      @case('rekap-siswa') Rekap per Siswa @break
      @case('siswa') Data Siswa @break
      @case('kelas') Data Kelas @break
      @case('tahun-ajaran') Data Tahun Ajaran @break
      @case('jenis-pembayaran') Data Jenis Pembayaran @break
      @case('iuran') Data Iuran @break
      @case('pembayaran') Data Pembayaran @break
      @case('penerimaan') Data Penerimaan @break
      @case('pengeluaran') Data Pengeluaran @break
      @case('jurnal') Jurnal Umum @break
    @endswitch
    ({{ $from }}—{{ $to }})
  </h3>

  <div class="mb-3">
  <a href="{{ route('laporan.export-excel', ['type'=>$type,'date_from'=>$from,'date_to'=>$to]) }}"
     class="btn btn-success">
    Export Excel
  </a>
  <a href="{{ route('laporan.cetak-pdf', ['type'=>$type,'date_from'=>$from,'date_to'=>$to]) }}"
     class="btn btn-danger">
    Cetak PDF
  </a>
</div>
  <a href="{{ route('laporan.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

  @if($data->isEmpty())
    <div class="alert alert-info">Tidak ada data untuk periode ini.</div>
    @return
  @endif

  @switch($type)

    @case('spp')
      <table class="table table-striped">
        <thead><tr><th>Tgl Bayar</th><th>Siswa</th><th>Jenis</th><th>Jumlah</th></tr></thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>{{ $d['tgl_bayar'] }}</td>
            <td>{{ $d['siswa'] }}</td>
            <td>{{ $d['jenis'] }}</td>
            <td>Rp {{ number_format($d['jumlah'],0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @break

    @case('keuangan')
      <table class="table table-bordered">
        <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Debit</th><th>Kredit</th></tr></thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>{{ $d['tanggal'] }}</td>
            <td>{{ $d['keterangan'] }}</td>
            <td>Rp {{ number_format($d['debit'],0,',','.') }}</td>
            <td>Rp {{ number_format($d['kredit'],0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @break

    @case('rekap-siswa')
      <table class="table table-hover">
        <thead><tr><th>Siswa</th><th>Dibayar</th><th>Tunggakan</th></tr></thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>{{ $d['siswa'] }}</td>
            <td>Rp {{ number_format($d['dibayar'],0,',','.') }}</td>
            <td>Rp {{ number_format($d['tunggakan'],0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @break

    {{-- GENERIC DATA TABLE FOR ALL OTHER MODELS --}}
    @default
      {{-- Ambil kolom pertama dari koleksi untuk header --}}
      @php
        $first = $data->first();
        $headers = $first ? array_keys((array)$first) : [];
      @endphp

      <table class="table table-striped">
        <thead>
          <tr>
            @foreach($headers as $h)
              <th>{{ ucfirst(str_replace(['_','-'], ' ', $h)) }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($data as $row)
            <tr>
              @foreach($headers as $h)
                <td>{{ data_get($row, $h) }}</td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
  @endswitch
</div>
@endsection
