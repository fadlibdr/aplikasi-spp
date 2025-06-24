@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Kelola Status Siswa</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="form-inline mb-3">
        <label class="mr-2">Filter Kelas</label>
        <select name="kelas" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
    </form>

    <form action="{{ route('siswa.ref.apply') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaList as $s)
                <tr>
                    <td><input type="checkbox" class="siswa-checkbox" name="siswa_ids[]" value="{{ $s->id }}"></td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama_depan }} {{ $s->nama_belakang }}</td>
                    <td>{{ $s->kelas->nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group">
            <select name="kelas_id" class="form-control d-inline w-auto">
                <option value="">Pilih Kelas Baru...</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
            <button type="submit" name="action" value="naik" class="btn btn-info">Naik Kelas</button>
            <button type="submit" name="action" value="pindah" class="btn btn-secondary">Pindah Sekolah</button>
            <button type="submit" name="action" value="lulus" class="btn btn-success">Luluskan</button>
        </div>
    </form>
    {{ $siswaList->withQueryString()->links() }}
</div>
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    selectAll.addEventListener('change', function(){
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
