@extends('admin.template.master')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-gray-900 fw-bold">Selamat Datang, Admin!</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-chalkboard-teacher text-primary fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">Total Guru</h6>
                        <p class="fs-4 fw-bold text-primary mb-0">{{ $totalGuru }}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-user-graduate text-success fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">Total Siswa</h6>
                        <p class="fs-4 fw-bold text-success mb-0">{{ $totalSiswa }}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-book text-info fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">Mata Pelajaran</h6>
                        <p class="fs-4 fw-bold text-info mb-0">{{ $totalMapel }}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-building text-warning fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">Ruangan</h6>
                        <p class="fs-4 fw-bold text-warning mb-0">{{ $totalRuangan }}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-purple bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-shield-alt text-purple fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">User</h6>
                        <p class="fs-4 fw-bold text-purple mb-0">{{ $totalUser }}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 text-center"
                        style="width: 170px; height: 120px; padding: 16px;">
                        <div class="mx-auto bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px;">
                            <i class="fas fa-school text-secondary fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-1 text-gray-700 fs-6">Kelas</h6>
                        <p class="fs-4 fw-bold text-secondary mb-0">{{ $totalKelas }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5 class="fw-bold text-gray-800 mb-3">
                    <i class="fas fa-chart-bar me-2"></i>User Aktif Saat Ini ({{ $totalActive }})
                </h5>

                @if ($totalActive > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">Admin</span>
                            <span class="text-muted">{{ $activeAdmin }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-primary rounded-pill"
                                style="width: {{ $totalActive ? round(($activeAdmin / $totalActive) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">Guru</span>
                            <span class="text-muted">{{ $activeGuru }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-success rounded-pill"
                                style="width: {{ $totalActive ? round(($activeGuru / $totalActive) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">Siswa</span>
                            <span class="text-muted">{{ $activeSiswa }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-warning rounded-pill"
                                style="width: {{ $totalActive ? round(($activeSiswa / $totalActive) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info py-2 px-3 rounded-3">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada user aktif saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .card {
                width: calc(50% - 12px) !important;
                margin-bottom: 12px;
            }
        }

        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .text-gray-700 {
            color: #4b5563;
        }

        .bg-purple {
            background-color: #7e57c2 !important;
        }

        .text-purple {
            color: #7e57c2 !important;
        }
    </style>
@endsection
