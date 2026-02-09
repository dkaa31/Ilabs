@extends('siswa.template.master')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid">
    <!-- Header Personal -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-gray-900 fw-bold">Halo, {{ auth()->user()->nama }}!</h2>
            @if($kelas)
                <p class="text-muted">Kelas: {{ $kelas->nama }}</p>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="mb-3">Status Hari Ini</h5>
                    
                    @if(in_array(strtolower(now()->englishDayOfWeek), ['saturday', 'sunday']))
                        <div class="text-center py-3">
                            <span class="badge bg-info text-white">Hari ini libur</span>
                        </div>
                    @else
                        @if($absenHariIni)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2"> HADIR</span>
                                <small class="text-muted">Jam: {{ \Carbon\Carbon::parse($absenHariIni->created_at)->format('H.i') }}</small>
                            </div>
                        @elseif($izinAktif)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2"> {{ ucfirst($izinAktif->jenis) }}</span>
                                <small class="text-muted">{{ $izinAktif->tanggal_mulai }} – {{ $izinAktif->tanggal_selesai }}</small>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2"> Belum Absen</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-3">
                @if(!in_array(strtolower(now()->englishDayOfWeek), ['saturday', 'sunday']) && !$absenHariIni && !$izinAktif)
                    <a href="{{ route('absensi.qr') }}" class="btn btn-primary">
                        <i class="fas fa-qrcode me-2"></i>Scan Absensi
                    </a>
                @endif
                <a href="{{ route('izin.create') }}" class="btn btn-outline-success">
                    <i class="fas fa-file-alt me-2"></i>Ajukan Izin
                </a>
                <a href="{{ route('siswa.data') }}" class="btn btn-outline-info">
                    <i class="fas fa-user me-2"></i>Profil Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Izin Aktif -->
    @if($izinAktif)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-file-medical me-2"></i>Izin Aktif</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ ucfirst($izinAktif->jenis) }}</strong></p>
                    <p class="text-muted mb-1">{{ $izinAktif->tanggal_mulai }} – {{ $izinAktif->tanggal_selesai }}</p>
                    <small>Status: <span class="text-success">Disetujui</span></small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Riwayat Minggu Ini -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Minggu Ini</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($riwayat as $data)
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $data['tanggal']->translatedFormat('D, j M') }}</strong>
                                    @if($data['is_today'])
                                        <span class="badge bg-primary ms-2">Hari Ini</span>
                                    @endif
                                </div>
                                <div>
                                    @if($data['status'] == 'Hadir')
                                        <span class="text-success">✅ Hadir</span>
                                    @elseif($data['status'] == 'Alpa')
                                        <span class="text-danger">❗ Alpa</span>
                                    @else
                                        <span class="text-warning">📋 {{ $data['status'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection