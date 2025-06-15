@extends('layouts.app')
@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <a href="{{ route('kelas.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Kelas
  </a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nama Kelas</th>
        <th>Kapasitas</th>
        <th>Tahun Ajaran</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $k)
      <tr>
        <td>{{ $k->nama }}</td>
        <td>{{ $k->kapasitas }}</td>
        <td>{{ $k->tahunAjaran->nama }}</td>
        <td>
          <a href="{{ route('kelas.edit',$k) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('kelas.destroy',$k) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Hapus kelas ini?')">
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
