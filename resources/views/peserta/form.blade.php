<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $peserta->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $peserta->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Alamat</label>
    <textarea name="address" class="form-control">{{ old('address', $peserta->address ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label>No HP</label>
    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $peserta->no_hp ?? '') }}">
</div>
