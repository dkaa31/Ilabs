@extends('admin.template.master')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
    <form method="POST" action="{{ route('user.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required onchange="toggleFields()">
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="guru">Guru</option>
                <option value="siswa">Siswa</option>
            </select>
        </div>

        <div id="admin-fields" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control">
            </div>
        </div>

        <div id="guru-fields" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Pilih Guru</label>
                <select name="id_guru" class="form-select">
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->nama }} (NIP: {{ $guru->nip }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="siswa-fields" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Pilih Siswa</label>
                <select name="id_siswa" class="form-select">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswas as $siswa)
                        <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama }} (NIS: {{ $siswa->nis }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <script>
        function toggleFields() {
            const role = document.querySelector('select[name="role"]').value;
            document.getElementById('admin-fields').style.display = role === 'admin' ? 'block' : 'none';
            document.getElementById('guru-fields').style.display = role === 'guru' ? 'block' : 'none';
            document.getElementById('siswa-fields').style.display = role === 'siswa' ? 'block' : 'none';
        }
    </script>
@endsection
