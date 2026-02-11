@extends('admin.template.master')
@section('title', 'Jadwal')
@section('page-title', 'Jadwal Mengajar')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Jadwal {{ session('filter_hari', 'Senin') }}
            @if (session('filter_ruangan_id'))
                - {{ \App\Models\Ruangan::find(session('filter_ruangan_id'))?->nama }}
            @endif
        </h5>
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Jadwal
        </a>
    </div>

    <!-- Form Filter -->
    <div class="bg-white rounded shadow-sm p-3 mb-4">
        <form action="{{ route('jadwal.filter') }}" method="POST">
            @csrf

            <div class="d-flex gap-2 align-items-center">
                <select name="hari" class="form-select flex-grow-1">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                        <option value="{{ $h }}" {{ session('filter_hari') == $h ? 'selected' : '' }}>
                            {{ $h }}
                        </option>
                    @endforeach
                </select>

                <select name="id_ruangan" class="form-select flex-grow-1">
                    <option value="">Semua Ruangan</option>
                    @foreach ($ruangans as $r)
                        <option value="{{ $r->id_ruangan }}"
                            {{ session('filter_ruangan_id') == $r->id_ruangan ? 'selected' : '' }}>
                            {{ $r->nama }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-search"></i>
                </button>

                <a href="{{ route('jadwal.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-rotate-left"></i>
                </a>

            </div>
        </form>
    </div>

    <div class="bg-white rounded shadow-sm p-3">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Jam Ke</th>
                        <th>Waktu</th>
                        <th>Guru</th>
                        <th>Mapel</th>
                        <th>Kelas</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $j)
                        <tr>
                            <td>{{ $j->jam_ke }}</td>
                            <td>{{ $j->waktu_mulai }} - {{ $j->waktu_selesai }}</td>
                            <td>{{ $j->guru?->nama ?? '–' }}</td>
                            <td>{{ $j->mapel?->nama ?? 'Istirahat' }}</td>
                            <td>{{ $j->kelas?->nama ?? '–' }}</td>
                            <td>{{ $j->ruangan?->nama ?? '–' }}</td>
                            <td>
                                <a href="{{ route('jadwal.edit', $j) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jadwal.destroy', $j) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus jadwal ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $jadwals->links('pagination::bootstrap-4') }}
    </div>
    </div>
@endsection
