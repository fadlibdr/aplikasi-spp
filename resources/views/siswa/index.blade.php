@extends('layouts.app')
@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Tambah Siswa
    </a>

    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="form-inline">
      @csrf
      <div class="form-group">
        <input type="file" name="file" class="form-control-file" required>
      </div>
      <button type="submit" class="btn btn-success ml-2">
        <i class="fas fa-file-import"></i> Impor Excel
      </button>
    </form>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Foto</th>
        <th>NIS / NISN</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Email</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $s)
      <tr>
        <td>
          @if($s->foto)
            <img src="{{ asset('storage/'.$s->foto) }}"
                 alt="Foto {{ $s->nama_depan }}"
                 width="50" class="rounded-circle">
          @else
            –
          @endif
        </td>
        <td>{{ $s->nis }}<br><small>{{ $s->nisn }}</small></td>
        <td>{{ $s->nama_depan }} {{ $s->nama_belakang }}</td>
        <td>{{ $s->kelas->nama }}</td>
        <td>{{ $s->email }}</td>
        <td><span class="badge badge-{{ $s->status_siswa=='aktif'?'success':'secondary' }}">
              {{ ucfirst($s->status_siswa) }}
            </span>
        </td>
        <td>
          <a href="{{ route('siswa.edit',$s) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('siswa.destroy',$s) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Hapus siswa ini?')">
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
