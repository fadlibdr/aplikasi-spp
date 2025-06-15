@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-3">Detail Tagihan - {{ $siswa->nama_depan }} {{ $siswa->nama_belakang }}</h3>
    <p><strong>NIS:</strong> {{ $siswa->nis }} | <strong>NISN:</strong> {{ $siswa->nisn }}</p>

    @if ($iuran->isEmpty())
        <div class="alert alert-success">Tidak ada tagihan tertunda.</div>
    @else
        <div class="mb-3">
            <strong>Total Tagihan:</strong> Rp {{ number_format($totalTagihan, 0, ',', '.') }}
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Bulan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($iuran as $index => $tagihan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tagihan->jenisPembayaran->nama }}</td>
                        <td>Rp {{ number_format($tagihan->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                        <td>{{ $tagihan->bulan }}</td>
                        <td>{{ ucfirst($tagihan->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="#" class="btn btn-success">Bayar Sekarang (Midtrans)</a> {{-- Tombol dummy --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        <script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function (e) {
        e.preventDefault();
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran berhasil.");
                console.log(result);
            },
            onPending: function(result){
                alert("Menunggu pembayaran.");
                console.log(result);
            },
            onError: function(result){
                alert("Terjadi kesalahan.");
                console.log(result);
            },
            onClose: function(){
                alert("Pembayaran dibatalkan.");
            }
        });
    });
</script
    @endif
</div>
@endsection
