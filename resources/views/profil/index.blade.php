@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Sekolah</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($profil)
        <div class="card mb-4">
            <div class="card-header">
                <strong>{{ $profil->nama }}</strong>
            </div>
            <div class="card-body row">
                <div class="col-md-4">
                    @if($profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="img-fluid mb-3">
                    @endif
                    @if($profil->gambar)
                        <img src="{{ asset('storage/' . $profil->gambar) }}" alt="Gambar" class="img-fluid">
                    @endif
                </div>
                <div class="col-md-8">
                    <p><strong>Alamat:</strong> {{ $profil->alamat }}</p>
                    <p><strong>Email:</strong> {{ $profil->email }}</p>
                    <p><strong>Telepon:</strong> {{ $profil->telepon }}</p>
                    <p><strong>Kepala Sekolah:</strong> {{ $profil->kepala_sekolah }}</p>

                    <a href="{{ route('profil.edit', $profil->id) }}" class="btn btn-primary">Edit Profil</a>

                    <form action="{{ route('profil.destroy', $profil->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus profil sekolah?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Hapus Profil</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <p>Data profil sekolah belum tersedia.</p>
        <a href="{{ route('profil.create') }}" class="btn btn-success">Tambah Profil Sekolah</a>
    @endif
</div>
@endsection
