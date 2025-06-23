@extends('layouts.app')
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Event
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $e)
            <tr>
                <td>{{ $e->title }}</td>
                <td>{{ $e->start_date->format('Y-m-d') }}</td>
                <td>{{ $e->end_date ? $e->end_date->format('Y-m-d') : '-' }}</td>
                <td>
                    <a href="{{ route('events.edit', $e) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('events.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
