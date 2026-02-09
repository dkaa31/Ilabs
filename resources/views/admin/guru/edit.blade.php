@extends('admin.template.master')
@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('guru.update', $guru) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $guru->nama) }}" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $guru->nip) }}" required>
      @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Foto Saat Ini</label><br>
      @if($guru->foto)
        <img src="{{ asset('storage/' . $guru->foto) }}" width="100" class="rounded">
      @else
        <span class="text-muted">–</span>
      @endif
    </div>
    <div class="mb-3">
      <label class="form-label">Ganti Foto (Opsional)</label>
      <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
      @error('foto')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('guru.index') }}" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
@endsection