@extends('siswa.template.master')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Data Absensi')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4 fw-bold">Rekap Absensi Siswa</h5>

            <form method="POST" action="{{ route('siswa.absensi.data.filter') }}" class="mb-4">
                @csrf
                <div class="d-flex gap-2">

                    <select name="kelas" class="form-select" style="max-width:200px;">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelass as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">

                    <input type="text" name="search" class="form-control" placeholder="Cari nama siswa..."
                        value="{{ request('search') }}">

                    <select name="status" class="form-select" style="max-width:200px;">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Sudah Absen</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Absen</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>

                    <button class="btn btn-primary px-4">
                        <i class="fas fa-search"></i>
                    </button>

                    <a href="{{ route('siswa.absensi.data') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-rotate-left"></i>
                    </a>

                </div>
            </form>

            @if ($selectedKelas)
                <div class="mb-3">
                    <h6 class="text-muted small">
                        Data Absensi: <strong>{{ $selectedKelas }}</strong> |
                        Tanggal: <strong>{{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }}</strong>
                    </h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Hari</th>
                                <th>Tgl</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasil as $i => $data)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $data['nama'] }}</td>
                                    <td>{{ $data['kelas'] }}</td>
                                    <td>{{ $data['hari'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data['tanggal'])->format('d/m') }}</td>
                                    <td>{{ $data['jam_absen'] }}</td>
                                    <td class="text-center">
                                        @if ($data['status_tampil'] === 'Sudah Absen')
                                            <span class="badge bg-success">✓ Hadir</span>
                                        @elseif($data['status_tampil'] === 'Belum Absen')
                                            <span class="badge bg-warning text-dark">Belum</span>
                                        @else
                                            <span
                                                class="badge bg-info">{{ Str::limit($data['status_tampil'], 8, '') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        Tidak ada data siswa di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <p><i class="fas fa-filter me-2"></i>Pilih kelas dan tanggal untuk melihat rekap absensi.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
