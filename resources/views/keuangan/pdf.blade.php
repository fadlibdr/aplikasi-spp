<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px }
        th, td { border: 1px solid #000; padding: 5px }
        th { background: #eee }
    </style>
</head>
<body>
    <h3>Laporan Penerimaan</h3>
    <table>
        <thead><tr><th>Tanggal</th><th>Sumber</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
        <tbody>
            @foreach($penerimaan as $p)
            <tr>
                <td>{{ $p->tanggal }}</td>
                <td>
                    {{ $p->sumber }}
                    @if($p->pembayaran_id)
                        (Iuran: {{ $p->pembayaran->order_id ?? 'N/A' }})
                    @endif
                </td>
                <td>Rp{{ number_format($p->jumlah,0,',','.') }}</td>
                <td>{{ $p->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Laporan Pengeluaran</h3>
    <table>
        <thead><tr><th>Tanggal</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
        <tbody>
            @foreach($pengeluaran as $p)
            <tr>
                <td>{{ $p->tanggal }}</td>
                <td>{{ $p->kategori }}</td>
                <td>Rp{{ number_format($p->jumlah,0,',','.') }}</td>
                <td>{{ $p->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="text-align:center;margin-top:20px">
        <img src="data:image/png;base64,{{ $qr }}" alt="QR Code">
        <p>Scan untuk data JSON</p>
    </div>
</body>
</html>
