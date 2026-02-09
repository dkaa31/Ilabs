@extends('siswa.template.master')
@section('title', 'Daftar Guru')
@section('page-title', 'Daftar Guru')

@section('content')
<div class="bg-white rounded shadow-sm p-3">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <form method="GET" action="{{ route('siswa.guru') }}" class="mb-4">
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

        <a href="{{ route('siswa.guru') }}" class="btn btn-secondary px-4">
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
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center text-muted">Belum ada data guru.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $gurus->links() }}
</div>
@endsection