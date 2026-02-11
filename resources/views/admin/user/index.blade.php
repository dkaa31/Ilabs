@extends('admin.template.master')
@section('title', 'Daftar User')
@section('page-title', 'Daftar User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="{{ route('user.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Akun
        </a>
    </div>

    <div class="bg-white rounded shadow-sm p-3">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <form method="GET" class="mb-4">
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama / email">

                    <select name="role" class="form-select" style="max-width: 220px;">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>

                    <button class="btn btn-primary px-4">
                        <i class="fas fa-search"></i>
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-secondary px-4">
                        <i class="fas fa-rotate-left"></i>
                    </a>

                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus user ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
    </div>
@endsection
