@extends('guru.template.master')
@section('title', 'Daftar Siswa')
@section('page-title', 'Daftar Siswa')

@section('content')
    <div class="bg-white rounded shadow-sm p-3">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <form method="GET" class="mb-4">
                <div class="d-flex gap-2 align-items-center">

                    {{-- Search --}}
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama / NIS / NISN">

                    {{-- Filter Kelas --}}
                    <select name="id_kelas" class="form-select" style="max-width: 220px;">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}"
                                {{ request('id_siswa') == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Tombol Filter --}}
                    <button class="btn btn-primary px-4">
                        <i class="fas fa-search"></i>
                    </button>

                    {{-- Reset --}}
                    <a href="{{ url()->current() }}" class="btn btn-secondary px-4">
                        <i class="fas fa-rotate-left"></i>
                    </a>

                </div>
            </form>

            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $siswa)
                        <tr>
                            <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nisn }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas ? $siswa->kelas->nama : '–' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $siswas->links() }}
    </div>
@endsection
