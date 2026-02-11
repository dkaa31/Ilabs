@extends('guru.template.master')
@section('title', 'Daftar Guru')
@section('page-title', 'Daftar Guru')

@section('content')
    <div class="bg-white rounded shadow-sm p-3">
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
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
                                @if ($guru->foto)
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
