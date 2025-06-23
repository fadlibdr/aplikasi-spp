@extends('layouts.app')
@section('content')
<div class="container">
    <h3>{{ $event->exists ? 'Edit' : 'Tambah' }} Event</h3>
    <form action="{{ $action }}" method="POST">
        @csrf
        @if($event->exists)
            @method('PUT')
        @endif
        <div class="form-group">
            <label>Judul</label>
            <input name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}" required>
            @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}">
            @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <button class="btn btn-success">{{ $event->exists ? 'Perbarui' : 'Simpan' }}</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
