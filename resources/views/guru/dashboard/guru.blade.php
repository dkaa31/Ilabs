@extends('guru.template.master')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')
<div class="container-fluid">
    <!-- Header Personal -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-gray-900 fw-bold">Selamat pagi, {{ auth()->user()->name }}!</h2>
            @if($kelasBinaan)
                <p class="text-muted">Wali Kelas {{ $kelasBinaan->nama }}</p>
            @else
                <p class="text-muted">Belum ditugaskan sebagai wali kelas.</p>
            @endif
        </div>
    </div>

    <!-- Navigasi Tanggal -->
    @if($kelasBinaan)
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-center align-items-center">
                <a href="{{ route('guru.dashboard', ['tanggal' => $tanggalSekarang->copy()->subDay()->format('Y-m-d')]) }}" 
                   class="btn btn-outline-secondary me-2">
                    <li class="fas fa-angle-left"></li>
                </a>
                <span class="mx-3 fw-bold">{{ $tanggalSekarang->translatedFormat('l, j F Y') }}</span>
                <a href="{{ route('guru.dashboard', ['tanggal' => $tanggalSekarang->copy()->addDay()->format('Y-m-d')]) }}" 
                   class="btn btn-outline-secondary ms-2">
                    <li class="fas fa-angle-right"></li>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Mengajar Saat Ini (Hanya jika hari ini) -->
    @if($tanggalSekarang->isToday())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    @if($jadwalSaatIni)
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-success me-2">SEDANG MENGAJAR</span>
                            <small class="text-muted">
                                Sisa waktu: {{ now()->diffInMinutes(\Carbon\Carbon::parse($jadwalSaatIni->jam_selesai), false) }} menit
                            </small>
                        </div>
                        <h5 class="mb-1">{{ $jadwalSaatIni->mapel->nama ?? '-' }}</h5>
                        <p class="text-muted mb-2">
                            {{ $jadwalSaatIni->kelas->nama ?? '-' }} • {{ $jadwalSaatIni->ruangan->nama ?? '-' }}
                        </p>
                        <a href="{{ route('absensi.scan') }}" class="btn btn-primary">
                            <i class="fas fa-qrcode me-2"></i>SCAN ABSENSI
                        </a>
                    @else
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-warning text-dark me-2">BELUM ADA JADWAL</span>
                        </div>
                        @if($jadwalHariIni->isNotEmpty())
                            <?php
                                $jadwalBerikutnya = $jadwalHariIni->first();
                                $jamMulai = \Carbon\Carbon::parse($jadwalBerikutnya->jam_mulai)->format('H.i');
                            ?>
                            <p class="mb-2">Jadwal berikutnya:</p>
                            <h6 class="mb-1">{{ $jamMulai }} – {{ $jadwalBerikutnya->mapel->nama ?? '-' }}</h6>
                            <p class="text-muted mb-2">
                                {{ $jadwalBerikutnya->kelas->nama ?? '-' }} • {{ $jadwalBerikutnya->ruangan->nama ?? '-' }}
                            </p>
                        @else
                            <p class="text-muted">Tidak ada jadwal mengajar hari ini.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Aksi Cepat -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('absensi.scan') }}" class="btn btn-outline-primary">
                    <i class="fas fa-qrcode me-2"></i>Scan Absensi
                </a>
                @if($kelasBinaan)
                    <a href="{{ route('guru.siswa') }}" class="btn btn-outline-success">
                        <i class="fas fa-users me-2"></i>Lihat Data Siswa
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Izin Menunggu -->
    @if($kelasBinaan && $izinMenunggu->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Izin Menunggu ({{ $izinMenunggu->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($izinMenunggu as $izin)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                            <div>
                                <strong>{{ $izin->siswa->nama }}</strong>
                                <br>
                                <small class="text-muted">{{ ucfirst($izin->jenis) }} • {{ $izin->tanggal_mulai }}</small>
                            </div>
                            <div>
                                <form action="{{ route('izin.approve', $izin) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('izin.reject', $izin) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger ms-1" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Grafik Kehadiran -->
    @if($kelasBinaan && ($totalHadir + $totalIzin + $totalSakit + $totalAlpa) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Kehadiran Kelas {{ $kelasBinaan->nama }} - {{ $tanggalSekarang->translatedFormat('l, j F Y') }}
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Hadir -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium text-success">Hadir</span>
                            <span class="text-muted">{{ $totalHadir }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-success rounded-pill" 
                                 style="width: {{ $maxKehadiran ? round(($totalHadir / $maxKehadiran) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Izin -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium text-warning">Izin</span>
                            <span class="text-muted">{{ $totalIzin }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-warning rounded-pill" 
                                 style="width: {{ $maxKehadiran ? round(($totalIzin / $maxKehadiran) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Sakit -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium text-danger">Sakit</span>
                            <span class="text-muted">{{ $totalSakit }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-danger rounded-pill" 
                                 style="width: {{ $maxKehadiran ? round(($totalSakit / $maxKehadiran) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Alpa -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium text-secondary">Alpa</span>
                            <span class="text-muted">{{ $totalAlpa }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-secondary rounded-pill" 
                                 style="width: {{ $maxKehadiran ? round(($totalAlpa / $maxKehadiran) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Jadwal Hari Ini -->
    @if($jadwalHariIni->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Jadwal {{ $tanggalSekarang->translatedFormat('l') }}</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($jadwalHariIni as $jadwal)
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i') }}–
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') }}
                                    </strong>
                                    {{ $jadwal->mapel->nama ?? '-' }}
                                </div>
                                <div class="text-muted">
                                    {{ $jadwal->kelas->nama ?? '-' }} • {{ $jadwal->ruangan->nama ?? '-' }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection