@extends('admin.template.master')
@section('title', 'Daftar Mapel')
@section('page-title', 'Daftar Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div></div>
  <a href="{{ route('mapel.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i>Tambah Mapel
  </a>
</div>

<div class="bg-white rounded shadow-sm p-3">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif
<div class="table-responsive">
  <form method="GET" class="mb-4">
  <div class="d-flex gap-2">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           placeholder="Cari kode atau nama mapel...">
    <button class="btn btn-primary px-4">
      <i class="fas fa-search"></i>
    </button>
    <a href="{{ route('mapel.index') }}" class="btn btn-secondary px-4">
      <i class="fas fa-rotate-left"></i>
    </a>

  </div>
</form>

  <table class="table table-bordered">
    <thead class="bg-light">
      <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($mapels as $mapel)
        <tr>
          <td>{{ $loop->iteration + ($mapels->currentPage() - 1) * $mapels->perPage() }}</td>
          <td>{{ $mapel->kode }}</td>
          <td>{{ $mapel->nama }}</td>
          <td>
            <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-sm btn-outline-primary me-1">
              <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" style="display:inline;">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus mapel ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

  {{ $mapels->links() }}
</div>
@endsection