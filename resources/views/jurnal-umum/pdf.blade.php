<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Jurnal Umum</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left
        }

        th {
            background: #f0f0f0
        }

        h3 {
            text-align: center
        }
    </style>
</head>

<body>
    <h3>Laporan Jurnal Umum</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $e)
                <tr>
                    <td>{{ $e->tanggal }}</td>
                    <td>{{ $e->keterangan }}</td>
                    <td>Rp {{ number_format($e->debit, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($e->kredit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>