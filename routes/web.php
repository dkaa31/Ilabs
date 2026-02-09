<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TrackUserActivity;
use App\Http\Controllers\{
    GuruController,
    MapelController,
    RuanganController,
    KelasController,
    SiswaController,
    JadwalController,
    DisplayController,
    UserController,
    AbsensiController,
    IzinController,
    UserSessionController,
    DashboardController,
    Auth\AuthenticatedSessionController
};

// ──────────────── ROUTES PUBLIK ────────────────
Route::get('/', fn() => redirect()->route('login'));

require __DIR__.'/auth.php'; // route login

// ──────────────── ROUTES YANG BUTUH LOGIN ────────────────
Route::middleware(['web', 'auth'])->group(function () {

    // Logout (bisa diakses semua role)
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard berdasarkan role
    // Dashboard berdasarkan role — SEMUA menuju DashboardController@index
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
Route::get('/siswa/dashboard', [DashboardController::class, 'index'])->name('siswa.dashboard');

    // Absensi & Izin (untuk guru & siswa)


    // Display (publik/internal)
    Route::get('/display/tampilan/ruangan', [DisplayController::class, 'selectRoomForSlide'])->name('display.tampilan.select');
    Route::get('/display/tampilan/{ruanganId}', [DisplayController::class, 'showSlide'])->name('display.tampilan');
    Route::get('/display/jadwal/ruangan', [DisplayController::class, 'selectRoomForSchedule'])->name('display.jadwal.select');
    Route::get('/display/jadwal/{ruanganId}', [DisplayController::class, 'showSchedule'])->name('display.jadwal');
    Route::get('/display/jadwal/{ruanganId}/hari/{hari}', [DisplayController::class, 'showDay'])->name('display.hari');

    // ──────────────── GURU ONLY: READ-ONLY ────────────────
    Route::middleware(['check_role:guru'])
        ->prefix('guru')
        ->group(function () {
            // Hanya lihat daftar guru — TIDAK BOLEH edit/tambah/hapus
            Route::get('/siswa', [SiswaController::class, 'indexSiswa'])->name('guru.siswa');

            Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
            Route::post('/absensi/verify', [AbsensiController::class, 'verify'])->name('absensi.verify');
            Route::get('/absensi/data', [AbsensiController::class, 'index'])->name('absensi.data');
            Route::post('/absensi/data', [AbsensiController::class, 'index'])->name('absensi.data.filter');

            Route::get('/izin/pengajuan', [IzinController::class, 'index'])->name('izin.index');
            Route::post('/izin/{izin}/approve', [IzinController::class, 'approve'])->name('izin.approve');
            Route::post('/izin/{izin}/reject', [IzinController::class, 'reject'])->name('izin.reject');
        });

    // ──────────────── ADMIN ONLY: FULL CRUD + TRACKING ────────────────
    Route::middleware(['check_role:admin', TrackUserActivity::class])
        ->prefix('admin')
        ->group(function () {
            // Semua CRUD hanya untuk admin
            Route::resource('guru', GuruController::class);
            Route::resource('mapel', MapelController::class);
            Route::resource('ruangan', RuanganController::class);
            Route::resource('kelas', KelasController::class);
            Route::resource('siswa', SiswaController::class);
            Route::resource('user', UserController::class);

            // Jadwal
            Route::get('jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
            Route::post('jadwal/filter', [JadwalController::class, 'filter'])->name('jadwal.filter');
            Route::get('jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
            Route::post('jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
            Route::get('jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
            Route::put('jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
            Route::delete('jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

            // Manajemen sesi user (fitur admin)
            Route::get('user-sessions', [UserSessionController::class, 'index'])->name('admin.user-sessions');
            Route::delete('user-sessions/{session}', [UserSessionController::class, 'destroy'])->name('admin.user-sessions.destroy');
        });

            Route::middleware(['check_role:siswa'])
        ->prefix('siswa')
        ->group(function () {
            // Hanya lihat daftar guru — TIDAK BOLEH edit/tambah/hapus
            Route::get('/data', [SiswaController::class, 'indexDataSiswa'])->name('siswa.data');
            Route::get('/guru', [GuruController::class, 'indexGuruSiswa'])->name('siswa.guru');

            Route::get('/absensi/qr', [AbsensiController::class, 'showQr'])->name('absensi.qr');
            Route::get('/absensi/data', [AbsensiController::class, 'indexSiswa'])->name('siswa.absensi.data');
            Route::post('/absensi/data', [AbsensiController::class, 'indexSiswa'])->name('siswa.absensi.data.filter');

            Route::get('/izin/ajukan', [IzinController::class, 'create'])->name('izin.create');
            Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
        });
});