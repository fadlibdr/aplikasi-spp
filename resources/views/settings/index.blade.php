@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>@endif

        <h3>Pengaturan Aplikasi</h3>
        <form action="{{ route('settings.update') }}" method="POST" class="mb-5">
            @csrf
            {{-- dasar --}}
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama Aplikasi</label>
                    <input name="app_name" class="form-control" value="{{ old('app_name', $settings['app_name']) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>URL Aplikasi</label>
                    <input name="app_url" class="form-control" value="{{ old('app_url', $settings['app_url']) }}">
                </div>
            </div>

            <h5>Midtrans</h5>
            <div class="form-row">
                <div class="form-group col">
                    <label>Server Key</label>
                    <input name="midtrans_server_key" class="form-control"
                        value="{{ old('midtrans_server_key', $settings['midtrans_server_key']) }}">
                </div>
                <div class="form-group col">
                    <label>Client Key</label>
                    <input name="midtrans_client_key" class="form-control"
                        value="{{ old('midtrans_client_key', $settings['midtrans_client_key']) }}">
                </div>
            </div>

            <h5>Email (SMTP)</h5>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Mailer</label>
                    <input name="mail_mailer" class="form-control"
                        value="{{ old('mail_mailer', $settings['mail_mailer']) }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Host</label>
                    <input name="mail_host" class="form-control" value="{{ old('mail_host', $settings['mail_host']) }}">
                </div>
                <div class="form-group col-md-2">
                    <label>Port</label>
                    <input name="mail_port" class="form-control" value="{{ old('mail_port', $settings['mail_port']) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Encryption</label>
                    <input name="mail_encryption" class="form-control"
                        value="{{ old('mail_encryption', $settings['mail_encryption']) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Username</label>
                    <input name="mail_username" class="form-control"
                        value="{{ old('mail_username', $settings['mail_username']) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Password</label>
                    <input name="mail_password" type="password" class="form-control"
                        value="{{ old('mail_password', $settings['mail_password']) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>From Address</label>
                    <input name="mail_from_address" class="form-control"
                        value="{{ old('mail_from_address', $settings['mail_from_address']) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>From Name</label>
                    <input name="mail_from_name" class="form-control"
                        value="{{ old('mail_from_name', $settings['mail_from_name']) }}">
                </div>
            </div>

            <h5>Activation Key</h5>
            <div class="form-group">
                <label>Activation Key</label>
                <input name="activation_key" class="form-control"
                    value="{{ old('activation_key', $settings['activation_key']) }}">
            </div>

            <h5>Auto Backup</h5>
            <div class="form-row">
            <div class="form-group col-md-4">
                <label>Frekuensi Backup</label>
                <select name="backup_frequency" class="form-control" required>
                @foreach(['daily'=>'Harian','weekly'=>'Mingguan','monthly'=>'Bulanan','yearly'=>'Tahunan'] as $val=>$label)
                    <option value="{{ $val }}"
                    {{ old('backup_frequency',$settings['backup_frequency'])===$val?'selected':'' }}>
                    {{ $label }}
                    </option>
                @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Jumlah Backup</label>
                <input type="number" name="backup_max_files" class="form-control"
                    min="1"
                    value="{{ old('backup_max_files',$settings['backup_max_files']) }}"
                    required>
            </div>
            </div>

            <button class="btn btn-primary">Simpan Pengaturan</button>
        </form>

        <h3>Backup & Restore Database</h3>

        {{-- tombol backup sekarang --}}
        <form action="{{ route('settings.backup') }}" method="POST" class="mb-3">
            @csrf
            <button class="btn btn-success">Backup Sekarang</button>
        </form>

        {{-- form upload untuk restore dari file --}}
        <form action="{{ route('settings.restore.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="form-group">
                <label>Upload Backup ZIP untuk Restore</label>
                <input type="file" name="backup_file" class="form-control-file" required>
            </div>
            <button class="btn btn-warning">Upload & Restore</button>
        </form>

        {{-- daftar backup yang ada --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>File Backup</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $file)
                    <tr>
                        <td>{{ $file }}</td>
                        <td>
                            {{-- download --}}
                            <a href="{{ route('settings.backup.download', $file) }}" class="btn btn-info btn-sm">Download</a>

                            {{-- restore pilih dari list tetap ada --}}
                            <form action="{{ route('settings.restore') }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="file" value="{{ $file }}">
                                <button class="btn btn-warning btn-sm" onclick="return confirm('Restore dari {{ $file }}?')">
                                    Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Belum ada backup.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection