@extends('layouts.app')
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'tahun-ajaran' ? 'active' : '' }}" href="{{ route('tahun-ajaran.index', ['tab' => 'tahun-ajaran']) }}">Tahun Ajaran</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'kelas' ? 'active' : '' }}" href="{{ route('tahun-ajaran.index', ['tab' => 'kelas']) }}">Kelas</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ $activeTab === 'tahun-ajaran' ? 'show active' : '' }}" id="tahun-ajaran" role="tabpanel">
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
                    @foreach($tahunData as $ta)
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
                            <a href="{{ route('tahun-ajaran.edit', ['tahun_ajaran' => $ta->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('tahun-ajaran.destroy', $ta) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tahunData->appends(['tab' => 'tahun-ajaran', 'kelas_page' => request('kelas_page')])->links() }}
        </div>
        <div class="tab-pane fade {{ $activeTab === 'kelas' ? 'show active' : '' }}" id="kelas" role="tabpanel">
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
                    @foreach($kelasData as $k)
                    <tr>
                        <td>{{ $k->nama }}</td>
                        <td>{{ $k->kapasitas }}</td>
                        <td>{{ $k->tahunAjaran->nama }}</td>
                        <td>
                            <a href="{{ route('kelas.edit',$k) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('kelas.destroy',$k) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $kelasData->appends(['tab' => 'kelas', 'ta_page' => request('ta_page')])->links() }}
        </div>
    </div>
</div>
@endsection
