@extends('admin.template.master')
@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')

@section('content')
    <div class="bg-white rounded shadow-sm p-4">
        <form action="{{ route('kelas.update', $kelas) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $kelas->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Wali Kelas</label>
                <select name="id_guru" class="form-select @error('id_guru') is-invalid @enderror">
                    <option value="">– Pilih Guru –</option>
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id_guru }}"
                            {{ (old('id_guru') ?? $kelas->id_guru) == $guru->id_guru ? 'selected' : '' }}>
                            {{ $guru->nama }}
                        </option>
                    @endforeach
                </select>
                @error('id_guru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
@endsection
