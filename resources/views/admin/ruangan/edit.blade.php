@extends('admin.template.master')
@section('title', 'Edit Ruangan')
@section('page-title', 'Edit Ruangan')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('ruangan.update', $ruangan) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nama Ruangan</label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $ruangan->nama) }}" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Penanggung Jawab Lab</label>
      <select name="id_guru" class="form-select @error('id_guru') is-invalid @enderror">
        <option value="">– Pilih Guru –</option>
        @foreach($gurus as $guru)
          <option value="{{ $guru->id_guru }}" {{ (old('id_guru') ?? $ruangan->id_guru) == $guru->id_guru ? 'selected' : '' }}>
            {{ $guru->nama }}
          </option>
        @endforeach
      </select>
      @error('id_guru')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('ruangan.index') }}" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
@endsection