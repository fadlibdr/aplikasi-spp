@extends('layouts.app')

@section('content')
<h4>Tambah Transaksi Keuangan</h4>

<form action="{{ route('keuangan.store') }}" method="POST">
    @csrf
    <div class="form-group mb-2">
        <label>Tipe Transaksi</label>
        <select name="tipe" class="form-control" required onchange="toggleForm(this.value)">
            <option value="penerimaan">Penerimaan</option>
            <option value="pengeluaran">Pengeluaran</option>
        </select>
    </div>
    <div id="penerimaan-form">
        <div class="form-group mb-2">
            <label>Sumber Dana</label>
            <input type="text" name="sumber" class="form-control" placeholder="Contoh: Donasi, Sponsor, dll">
        </div>
    </div>
    <div id="pengeluaran-form" style="display: none;">
        <div class="form-group mb-2">
            <label>Kategori Pengeluaran</label>
            <input type="text" name="kategori" class="form-control" placeholder="Contoh: ATK, Perbaikan, dll">
        </div>
    </div>
    <div class="form-group mb-2">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"></textarea>
    </div>
    <div class="form-group mb-2">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>
    <button class="btn btn-success">Simpan</button>
</form>

<script>
function toggleForm(value) {
    if(value === 'penerimaan') {
        document.getElementById('penerimaan-form').style.display = '';
        document.getElementById('pengeluaran-form').style.display = 'none';
    } else {
        document.getElementById('penerimaan-form').style.display = 'none';
        document.getElementById('pengeluaran-form').style.display = '';
    }
}
</script>
@endsection
