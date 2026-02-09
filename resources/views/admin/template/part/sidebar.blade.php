
  <div class="px-3 py-3 mb-4 border-bottom">
    <div class="d-flex align-items-center gap-3">
      @if(Auth::user()->foto)
        <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
             class="rounded-circle border" width="45" height="45" alt="User">
      @else
        <div class="d-flex align-items-center justify-content-center rounded-circle border bg-light text-dark" 
             style="width:45px; height:45px; font-weight:bold;">
          {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
        </div>
      @endif
      <div>
        <div class="fw-semibold">{{ Auth::user()->nama }}</div>
        <small class="text-secondary">{{ ucfirst(Auth::user()->role) }}</small>
      </div>
    </div>
  </div>

  <!-- Beranda -->
  <div class="px-3 mb-3">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <i class="fas fa-home me-2"></i> Beranda
    </a>
  </div>

  <!-- Menu Data -->
  <div class="px-3 mb-3">
    <a class="nav-link d-flex align-items-center text-dark menu-parent" href="#" data-target="submenu-data">
      <i class="fas fa-folder me-2"></i> Data
      <i class="fas fa-chevron-down ms-auto transition rotate-icon"></i>
    </a>
    <div id="submenu-data" class="submenu ms-3 mt-1" style="display: none;">
      <a class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}" href="{{ route('guru.index') }}">
        <i class="fas fa-chalkboard-teacher me-2"></i> Guru
      </a>
      <a class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
        <i class="fas fa-graduation-cap me-2"></i> Siswa
      </a>
      <a class="nav-link {{ request()->routeIs('mapel.*') ? 'active' : '' }}" href="{{ route('mapel.index') }}">
        <i class="fas fa-book-open me-2"></i> Mata Pelajaran
      </a>
      <a class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}" href="{{ route('ruangan.index') }}">
        <i class="fas fa-door-open me-2"></i> Ruangan
      </a>
      <a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
        <i class="fas fa-user me-2"></i> User
      </a>
      <a class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}" href="{{ route('kelas.index') }}">
        <i class="fas fa-school me-2"></i> Kelas
      </a>
      <a class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}" href="{{ route('jadwal.index') }}">
        <i class="fas fa-calendar-alt me-2"></i> Jadwal
      </a>
    </div>
  </div>

<div class="px-3 mb-3">
  <a class="nav-link {{ request()->routeIs('admin.user-sessions') ? 'active' : '' }}" 
      href="{{ route('admin.user-sessions') }}">
      <i class="fas fa-users me-2"></i> User Aktif
  </a>
</div>

<!-- Display -->
<div class="px-3">
  <a class="nav-link d-flex align-items-center text-dark menu-parent" href="#" data-target="submenu-display">
    <i class="fas fa-desktop me-2"></i> Display
    <i class="fas fa-chevron-down ms-auto transition rotate-icon"></i>
  </a>
  <div id="submenu-display" class="submenu ms-3 mt-1" style="display: none;">
    <a class="nav-link" href="{{ route('display.tampilan.select') }}"
    target="_blank" 
    rel="noopener noreferrer">
      <i class="fas fa-tv me-2"></i> Tampilan
    </a>
    <a class="nav-link" href="{{ route('display.jadwal.select') }}"
    target="_blank" 
    rel="noopener noreferrer">
      <i class="fas fa-calendar-alt me-2"></i> Jadwal
    </a>
  </div>
</div>

  <!-- Logout -->
  <div class="px-3 mt-4 pt-3 border-top">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
      @csrf
      <button type="submit" class="nav-link text-danger d-flex align-items-center w-100">
        <i class="fas fa-sign-out-alt me-2"></i> Keluar
      </button>
    </form>
  </div>