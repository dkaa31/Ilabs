<div class="px-3 py-3 mb-4 border-bottom">
    <div class="d-flex align-items-center gap-3">
        @if (Auth::user()->foto)
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="rounded-circle border" width="45"
                height="45" alt="User">
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

<!-- dashboard -->
<div class="px-3 mb-3">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('siswa.dashboard') }}">
        <i class="fas fa-home me-2"></i> Beranda
    </a>
</div>

<!-- data -->
<div class="px-3 mb-3">
    <a class="nav-link d-flex align-items-center text-dark menu-parent" href="#" data-target="submenu-data">
        <i class="fas fa-folder me-2"></i> Data
        <i class="fas fa-chevron-down ms-auto transition rotate-icon"></i>
    </a>
    <div id="submenu-data" class="submenu ms-3 mt-1" style="display: none;">
        <a class="nav-link {{ request()->routeIs('siswa.guru') ? 'active' : '' }}" href="{{ route('siswa.guru') }}">
            <i class="fas fa-chalkboard-teacher me-2"></i> Guru
        </a>
        <a class="nav-link {{ request()->routeIs('siswa.data') ? 'active' : '' }}" href="{{ route('siswa.data') }}">
            <i class="fas fa-graduation-cap me-2"></i> Siswa
        </a>
    </div>
</div>

<div class="px-3 mb-3">
    <a class="nav-link d-flex align-items-center text-dark menu-parent" href="#" data-target="submenu-absensi">
        <i class="fas fa-qrcode me-2"></i> Absensi QR
        <i class="fas fa-chevron-down ms-auto transition rotate-icon"></i>
    </a>
    <div id="submenu-absensi" class="submenu ms-3 mt-1" style="display: none;">
        <a class="nav-link {{ request()->routeIs('absensi.data') ? 'active' : '' }}"
            href="{{ route('siswa.absensi.data') }}">
            <i class="fas fa-clipboard-list me-2"></i> Data Absen
        </a>
        <a class="nav-link {{ request()->routeIs('absensi.qr') ? 'active' : '' }}" href="{{ route('absensi.qr') }}">
            <i class="fas fa-qrcode me-2"></i> QR Saya
        </a>
        <a class="nav-link {{ request()->routeIs('izin.ajukan') ? 'active' : '' }}" href="{{ route('izin.create') }}">
            <i class="fas fa-clipboard-list me-2"></i> Pengajuan Absen
        </a>
    </div>
</div>

<!-- display -->
<div class="px-3">
    <a class="nav-link d-flex align-items-center text-dark menu-parent" href="#" data-target="submenu-display">
        <i class="fas fa-desktop me-2"></i> Display
        <i class="fas fa-chevron-down ms-auto transition rotate-icon"></i>
    </a>
    <div id="submenu-display" class="submenu ms-3 mt-1" style="display: none;">
        <a class="nav-link" href="{{ route('display.tampilan.select') }}" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-tv me-2"></i> Tampilan
        </a>
        <a class="nav-link" href="{{ route('display.jadwal.select') }}" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-calendar-alt me-2"></i> Jadwal
        </a>
    </div>
</div>

<!-- keluar -->
<div class="px-3 mt-4 pt-3 border-top">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="nav-link text-danger d-flex align-items-center w-100">
            <i class="fas fa-sign-out-alt me-2"></i> Keluar
        </button>
    </form>
</div>
