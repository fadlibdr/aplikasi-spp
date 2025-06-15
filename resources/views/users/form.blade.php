@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>{{ $mode === 'create' ? 'Tambah' : 'Edit' }} User</h3>

        <form action="{{ $action }}" method="POST">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            @if($mode === 'create')
                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input name="password_confirmation" type="password" class="form-control" required>
                </div>
            @else
                <div class="form-group">
                    <label>Password <small>(kosongkan jika tidak diubah)</small></label>
                    <input name="password" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input name="password_confirmation" type="password" class="form-control">
                </div>
            @endif

            <div class="form-group">
                <label>Roles</label>
                <select name="roles[]" class="form-control" multiple required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ in_array($role->name, old('roles', $selected)) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success">{{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection