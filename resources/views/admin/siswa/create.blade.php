@extends('admin.template.master')
@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('siswa.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">NIS</label>
      <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
      @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">NISN</label>
      <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}" required>
      @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
      @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Kelas</label>
      <select name="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
        <option value="">– Pilih Kelas –</option>
        @foreach($kelas as $k)
          <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
            {{ $k->nama }}
          </option>
        @endforeach
      </select>
      @error('id_kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('siswa.index') }}" class="btn btn-secondary ms-2">Batal</a>
  </form>
</div>
@endsection