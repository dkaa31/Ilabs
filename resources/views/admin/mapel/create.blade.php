@extends('admin.template.master')
@section('title', 'Tambah Mapel')
@section('page-title', 'Tambah Mata Pelajaran')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('mapel.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">Kode Mapel</label>
      <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" required>
      @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Nama Mapel</label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('mapel.index') }}" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
@endsection