@extends('admin.template.master')
@section('title', 'Daftar Ruangan')
@section('page-title', 'Daftar Ruangan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="{{ route('ruangan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Ruangan
        </a>
    </div>

    <div class="bg-white rounded shadow-sm p-3">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <form method="GET" action="{{ route('ruangan.index') }}" class="mb-3">
                <div class="d-flex gap-2">

                    <input type="text" name="keyword" class="form-control"
                        placeholder="Cari nama ruangan / penanggung jawab..." value="{{ request('keyword') }}">

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search"></i>
                    </button>

                    <a href="{{ route('ruangan.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-rotate-left"></i>
                    </a>

                </div>
            </form>

            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Penanggung Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ruangans as $ruangan)
                        <tr>
                            <td>{{ $loop->iteration + ($ruangans->currentPage() - 1) * $ruangans->perPage() }}</td>
                            <td>{{ $ruangan->nama }}</td>
                            <td>
                                {{ $ruangan->guru ? $ruangan->guru->nama : '–' }}
                            </td>
                            <td>
                                <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ruangan.destroy', $ruangan) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus ruangan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $ruangans->links('pagination::bootstrap-4') }}
    </div>
    </div>
@endsection
