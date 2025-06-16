@extends('layouts.app')
@section('content')
  <div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Tahun Ajaran
    </a>

    <table class="table table-bordered">
    <thead>
      <tr>
      <th>Nama</th>
      <th>Semester</th>
      <th>Bulan Aktif</th>
      <th>Aktif</th>
      <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $ta)
      <tr>
      <td>{{ $ta->nama }}</td>
      <td>{{ $ta->semester }}</td>
      <td>
      @foreach($ta->bulan as $b)
      <span class="badge badge-info">{{ $b->nama }}</span>
      @endforeach
      </td>
      <td>
      @if($ta->aktif)
      <span class="badge badge-success">Ya</span>
      @else
      <span class="badge badge-secondary">Tidak</span>
      @endif
      </td>
      <td>
      <a href="{{ route('tahun-ajaran.edit', ['tahun_ajaran' => $ta->id]) }}"
      class="btn btn-sm btn-warning">Edit</a>

      <form action="{{ route('tahun-ajaran.destroy', $ta) }}" method="POST" class="d-inline"
      onsubmit="return confirm('Hapus tahun ajaran ini?')">
      @csrf @method('DELETE')
      <button class="btn btn-sm btn-danger">Hapus</button>
      </form>
      </td>
      </tr>
    @endforeach
    </tbody>
    </table>

    {{ $data->links() }}
  </div>
@endsection