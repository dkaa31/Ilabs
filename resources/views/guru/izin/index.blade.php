@extends('guru.template.master')

@section('title', 'Pengajuan Izin')
@section('page-title', 'Daftar Pengajuan Izin')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-4 fw-bold">📄 Pengajuan Izin Menunggu Persetujuan</h5>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($pengajuan->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox me-1"></i>
                Tidak ada pengajuan izin menunggu.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Siswa</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Surat</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($pengajuan as $no => $izin)
                    <tr>
                        <td class="text-center">{{ $no + 1 }}</td>

                        <td>{{ $izin->siswa->nama }}</td>

                        <td class="text-center">
                            <span class="badge bg-info">
                                {{ ucfirst($izin->jenis) }}
                            </span>
                        </td>

                        <td>
                            {{ $izin->tanggal_mulai->format('d-m-Y') }}
                            @if($izin->tanggal_selesai)
                                <br>
                                <small class="text-muted">
                                    s/d {{ $izin->tanggal_selesai->format('d-m-Y') }}
                                </small>
                            @endif
                        </td>

                        <td>
                            {{ $izin->alasan ?? '-' }}
                        </td>

                        <td class="text-center">
                            @if($izin->file_surat)
                                <a href="{{ asset('storage/' . $izin->file_surat) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-info">
                                   <i class="fas fa-file"></i>
                                   Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td class="text-center">
                            <form action="{{ route('izin.approve', $izin) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Setujui izin ini?')">
                                    ✓ Setujui
                                </button>
                            </form>

                            <form action="{{ route('izin.reject', $izin) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tolak izin ini?')">
                                    ✗ Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @endif

    </div>
</div>
@endsection
