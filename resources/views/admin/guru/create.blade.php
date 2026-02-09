@extends('admin.template.master')
@section('title', 'Tambah Guru')
@section('page-title', 'Tambah Guru')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required>
      @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Foto (Opsional)</label>
      <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
      @error('foto')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('guru.index') }}" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
@endsection