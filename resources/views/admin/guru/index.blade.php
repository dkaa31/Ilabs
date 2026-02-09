@extends('admin.template.master')
@section('title', 'Daftar Guru')
@section('page-title', 'Daftar Guru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div></div>
  <a href="{{ route('guru.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i>Tambah Guru
  </a>
</div>

<div class="bg-white rounded shadow-sm p-3">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <form method="GET" action="{{ route('guru.index') }}" class="mb-4">
    <div class="d-flex gap-2">

        <input 
            type="text" 
            name="keyword" 
            class="form-control"
            placeholder="Cari nama / NIP guru..."
            value="{{ request('keyword') }}"
        >

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-search"></i>
        </button>

        <a href="{{ route('guru.index') }}" class="btn btn-secondary px-4">
            <i class="fas fa-rotate-left"></i>
        </a>

    </div>
</form>

    <table class="table table-bordered">
      <thead class="bg-light">
        <tr>
          <th>No</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>NIP</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($gurus as $guru)
          <tr>
            <td>{{ $loop->iteration + ($gurus->currentPage() - 1) * $gurus->perPage() }}</td>
            <td>
              @if($guru->foto)
                <img src="{{ asset('storage/' . $guru->foto) }}" width="40" class="rounded">
              @else
                <span class="text-muted">–</span>
              @endif
            </td>
            <td>{{ $guru->nama }}</td>
            <td>{{ $guru->nip }}</td>
            <td>
              <a href="{{ route('guru.edit', $guru) }}" class="btn btn-sm btn-outline-primary me-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('guru.destroy', $guru) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus guru ini?')">
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

  {{ $gurus->links() }}
</div>
@endsection