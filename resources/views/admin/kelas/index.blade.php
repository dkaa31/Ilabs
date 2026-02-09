@extends('admin.template.master')
@section('title', 'Daftar Kelas')
@section('page-title', 'Daftar Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div></div>
  <a href="{{ route('kelas.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i>Tambah Kelas
  </a>
</div>

<div class="bg-white rounded shadow-sm p-3">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif
<div class="table-responsive">
  <form method="GET" class="mb-3">
  <div class="d-flex gap-2 align-items-center">
      <input 
          type="text"
          name="search"
          value="{{ request('search') }}"
          class="form-control"
          placeholder="Cari nama kelas / wali kelas">
      <button class="btn btn-primary px-4">
          <i class="fas fa-search"></i>
      </button>
      <a href="{{ url()->current() }}" class="btn btn-secondary px-4">
          <i class="fas fa-rotate-left"></i>
      </a>

  </div>
</form>

  <table class="table table-bordered">
    <thead class="bg-light">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Wali Kelas</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($kelas as $k)
        <tr>
          <td>{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
          <td>{{ $k->nama }}</td>
          <td>{{ $k->waliKelas ? $k->waliKelas->nama : '–' }}</td>
          <td>
            <a href="{{ route('kelas.edit', $k) }}" class="btn btn-sm btn-outline-primary me-1">
              <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('kelas.destroy', $k) }}" method="POST" style="display:inline;">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus kelas ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

  {{ $kelas->links() }}
</div>
@endsection