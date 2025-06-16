
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Profil Sekolah</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profil.store') }}" method="POST" enctype="multipart/form-data">
        @include('profil.form')

        <button type="submit" class="btn btn-success">Simpan Profil</button>
        <a href="{{ route('profil.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
