@extends('layouts.app')
@section('content')
<div class="container">

  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link {{ $active==='jenis'?'active':'' }}"
         href="{{ route('jenis.index') }}">Jenis Pembayaran</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ $active==='iuran'?'active':'' }}"
         href="{{ route('iuran.index') }}">Iuran</a>
    </li>
  </ul>

  @if($active==='jenis')
    <a href="{{ route('jenis.create') }}" class="btn btn-primary mb-2">
      <i class="fas fa-plus"></i> Tambah Jenis
    </a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Kode</th><th>Nama</th><th>Nominal</th><th>Frekuensi</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($jenisList as $j)
        <tr>
          <td>{{ $j->kode }}</td>
          <td>{{ $j->nama }}</td>
          <td>{{ number_format($j->nominal) }}</td>
          <td>{{ $j->frekuensi }}</td>
          <td>
            <a href="{{ route('jenis.edit',$j) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('jenis.destroy',$j) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Yakin?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

  @else {{-- iuran --}}
    <a href="{{ route('iuran.create') }}" class="btn btn-primary mb-2">
      <i class="fas fa-plus"></i> Tambah Iuran
    </a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Siswa</th><th>Jenis</th><th>Bulan</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($iuranList as $u)
        <tr>
          <td>{{ $u->siswa->nama_depan }} {{ $u->siswa->nama_belakang }}</td>
          <td>{{ $u->jenisPembayaran->nama }}</td>
          <td>{{ $u->bulan }}</td>
          <td>{{ ucfirst($u->status) }}</td>
          <td>
            <a href="{{ route('iuran.edit',$u) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('iuran.destroy',$u) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Yakin?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $iuranList->links() }}
  @endif

</div>
@endsection
