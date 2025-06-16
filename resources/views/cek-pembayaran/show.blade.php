@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-3">Detail Tagihan - {{ $siswa->nama_depan }} {{ $siswa->nama_belakang }}</h3>
    <p><strong>NIS:</strong> {{ $siswa->nis }} | <strong>NISN:</strong> {{ $siswa->nisn }}</p>

    @if ($iuran->isEmpty())
        <div class="alert alert-success">Tidak ada tagihan tertunda.</div>
    @else
        <div class="mb-3">
            <strong>Total Tagihan:</strong> Rp <span id="total-amount">{{ number_format($totalTagihan, 0, ',', '.') }}</span>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
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
                        <td><input type="checkbox" class="iuran-checkbox" data-nominal="{{ $tagihan->jenisPembayaran->nominal }}" value="{{ $tagihan->id }}"></td>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tagihan->jenisPembayaran->nama }}</td>
                        <td>Rp {{ number_format($tagihan->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                        <td>{{ $tagihan->bulan }}</td>
                        <td>{{ ucfirst($tagihan->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button id="pay-button" class="btn btn-success">Bayar Terpilih</button>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        <script type="text/javascript">
            const checkboxes = document.querySelectorAll('.iuran-checkbox');
            const totalEl = document.getElementById('total-amount');

            function format(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            function updateTotal() {
                let total = 0;
                checkboxes.forEach(cb => { if (cb.checked) total += parseInt(cb.dataset.nominal); });
                totalEl.textContent = format(total);
            }

            document.getElementById('select-all').addEventListener('change', function () {
                checkboxes.forEach(cb => { cb.checked = this.checked; });
                updateTotal();
            });
            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
            updateTotal();

            document.getElementById('pay-button').addEventListener('click', function (e) {
                e.preventDefault();
                const ids = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (ids.length === 0) {
                    alert('Pilih tagihan terlebih dahulu.');
                    return;
                }
                fetch('{{ route('cek-pembayaran.bayar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ iuran_ids: ids })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.token) {
                        window.snap.pay(res.token);
                    } else {
                        alert(res.error || 'Terjadi kesalahan');

                    }
                });
            });
        </script>
    @endif
</div>
@endsection
