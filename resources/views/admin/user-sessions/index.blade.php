@extends('admin.template.master')

@section('title', 'User Aktif')
@section('page-title', 'Monitoring User Aktif')

@section('content')

<div class="bg-white rounded shadow-sm p-3">

  <h5 class="mb-4">Daftar User yang Sedang Login</h5>

  {{-- FORM SEARCH --}}
  <form method="GET" action="{{ route('admin.user-sessions') }}" class="mb-4">
    <div class="d-flex gap-2">

        <input 
            type="text" 
            name="keyword" 
            class="form-control"
            placeholder="Cari nama user..."
            value="{{ request('keyword') }}"
        >

        <select name="role" class="form-select" style="max-width:220px;">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
        </select>

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-search"></i>
        </button>

        <a href="{{ route('admin.user-sessions') }}" class="btn btn-secondary px-4">
            <i class="fas fa-rotate-left"></i>
        </a>

    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success mb-3">
        {{ session('success') }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="bg-light">
        <tr>
          <th>User</th>
          <th>Role</th>
          <th>IP Address</th>
          <th>Terakhir Aktif</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
        @forelse($activeSessions as $session)
          <tr>
            <td>{{ $session->user->name }}</td>
            <td>{{ ucfirst($session->user->role) }}</td>
            <td>{{ $session->ip_address ?? '–' }}</td>
            <td>{{ $session->last_activity->diffForHumans() }}</td>
            <td>
              <form 
                action="{{ route('admin.user-sessions.destroy', $session) }}" 
                method="POST" 
                style="display:inline;"
                onsubmit="return confirm('Logout user ini?')">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="fas fa-sign-out-alt"></i>
                </button>

              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted">
              Tidak ada user yang sedang aktif.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@endsection
