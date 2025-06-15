@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Cek Pembayaran Siswa</h3>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('cek-pembayaran.show') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nisn">Masukkan NISN:</label>
            <input type="text" name="nisn" class="form-control" placeholder="Contoh: 1234567890" required value="{{ $nisn }}" @if($nisn) readonly @endif>
        </div>
        <button type="submit" class="btn btn-primary">Cek Tagihan</button>
    </form>
</div>
@endsection
