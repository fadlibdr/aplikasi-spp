<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan {{ $type }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h4>Laporan {{ $type }} ({{ $from }} – {{ $to }})</h4>
    <table>
        <thead>
            <tr>
                @foreach(collect((array) $data->first())->keys() as $h)
                    <th>{{ ucfirst(str_replace(['_', '-'], ' ', $h)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach((array) $row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
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