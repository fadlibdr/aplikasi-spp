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