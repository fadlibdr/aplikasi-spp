@extends('layouts.app')

@section('content')
<h4>Audit Logs</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Waktu</th>
            <th>User</th>
            <th>Method</th>
            <th>URL</th>
            <th>IP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name ?? 'Guest' }}</td>
            <td>{{ $log->method }}</td>
            <td>{{ $log->url }}</td>
            <td>{{ $log->ip_address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->links() }}
@endsection
