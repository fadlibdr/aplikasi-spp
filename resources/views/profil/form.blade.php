@csrf

<div class="form-group mb-3">
    <label>Nama Sekolah</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $profil->nama ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $profil->alamat ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $profil->email ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label>Telepon</label>
    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $profil->telepon ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label>Kepala Sekolah</label>
    <input type="text" name="kepala_sekolah" class="form-control" value="{{ old('kepala_sekolah', $profil->kepala_sekolah ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label>Logo Sekolah (jpg, png, max 2MB)</label>
    @if(!empty($profil->logo))
        <div>
            <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Sekolah" style="max-height:150px" class="img-thumbnail mb-2">
        </div>
    @endif
    <input type="file" name="logo" class="form-control-file" accept="image/*">
</div>

<div class="form-group mb-3">
    <label>Gambar Sekolah (jpg, png, max 4MB)</label>
    @if(!empty($profil->gambar))
        <div>
            <img src="{{ asset('storage/' . $profil->gambar) }}" alt="Gambar Sekolah" style="max-height:150px" class="img-thumbnail mb-2">
        </div>
    @endif
    <input type="file" name="gambar" class="form-control-file" accept="image/*">
</div>
