@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Pembayaran untuk {{ $iuran->siswa->nama_depan }} {{ $iuran->siswa->nama_belakang }}</h3>
        <p><strong>Jenis:</strong> {{ $iuran->jenisPembayaran->nama }}</p>
        <p><strong>Nominal:</strong> Rp {{ number_format($iuran->jenisPembayaran->nominal, 0, ',', '.') }}</p>

        <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    alert('Pembayaran berhasil');
                    window.location = '{{ route('pembayaran.index') }}';
                },
                onPending: function (result) {
                    alert('Menunggu pembayaran');
                    window.location = '{{ route('pembayaran.index') }}';
                },
                onError: function () {
                    alert('Pembayaran gagal');
                    window.location = '{{ route('pembayaran.index') }}';
                },
                onClose: function () {
                    alert('Anda menutup popup pembayaran');
                }
            });
        });
    </script>

@endsection