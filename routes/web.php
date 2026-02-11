<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TrackUserActivity;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\UserSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', fn () => redirect()->route('login'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Dashboard (semua role)
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/guru/dashboard', [DashboardController::class, 'index'])
        ->name('guru.dashboard');

    Route::get('/siswa/dashboard', [DashboardController::class, 'index'])
        ->name('siswa.dashboard');

    Route::get('/display/tampilan/ruangan', [DisplayController::class, 'selectRoomForSlide'])
        ->name('display.tampilan.select');

    Route::get('/display/tampilan/{ruanganId}', [DisplayController::class, 'showSlide'])
        ->name('display.tampilan');

    Route::get('/display/jadwal/ruangan', [DisplayController::class, 'selectRoomForSchedule'])
        ->name('display.jadwal.select');

    Route::get('/display/jadwal/{ruanganId}', [DisplayController::class, 'showSchedule'])
        ->name('display.jadwal');

    Route::get('/display/jadwal/{ruanganId}/hari/{hari}', [DisplayController::class, 'showDay'])
        ->name('display.hari');

    Route::middleware(['role:guru'])
        ->prefix('guru')
        ->group(function () {

            Route::get('/siswa', [SiswaController::class, 'indexSiswa'])
                ->name('guru.siswa');

            Route::get('/absensi/scan', [AbsensiController::class, 'scan'])
                ->name('absensi.scan');

            Route::post('/absensi/verify', [AbsensiController::class, 'verify'])
                ->name('absensi.verify');

            Route::get('/absensi/data', [AbsensiController::class, 'index'])
                ->name('absensi.data');

            Route::post('/absensi/data', [AbsensiController::class, 'index'])
                ->name('absensi.data.filter');

            Route::get('/izin/pengajuan', [IzinController::class, 'index'])
                ->name('izin.index');

            Route::post('/izin/{izin}/approve', [IzinController::class, 'approve'])
                ->name('izin.approve');

            Route::post('/izin/{izin}/reject', [IzinController::class, 'reject'])
                ->name('izin.reject');
        });

    Route::middleware(['role:admin', TrackUserActivity::class])
        ->prefix('admin')
        ->group(function () {

            Route::resource('guru', GuruController::class);
            Route::resource('mapel', MapelController::class);
            Route::resource('ruangan', RuanganController::class);
            Route::resource('kelas', KelasController::class);
            Route::resource('siswa', SiswaController::class);
            Route::resource('user', UserController::class);

            // Jadwal
            Route::get('jadwal', [JadwalController::class, 'index'])
                ->name('jadwal.index');

            Route::post('jadwal/filter', [JadwalController::class, 'filter'])
                ->name('jadwal.filter');

            Route::get('jadwal/create', [JadwalController::class, 'create'])
                ->name('jadwal.create');

            Route::post('jadwal', [JadwalController::class, 'store'])
                ->name('jadwal.store');

            Route::get('jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])
                ->name('jadwal.edit');

            Route::put('jadwal/{jadwal}', [JadwalController::class, 'update'])
                ->name('jadwal.update');

            Route::delete('jadwal/{jadwal}', [JadwalController::class, 'destroy'])
                ->name('jadwal.destroy');

            // User Session Monitor
            Route::get('user-sessions', [UserSessionController::class, 'index'])
                ->name('admin.user-sessions');

            Route::delete('user-sessions/{session}', [UserSessionController::class, 'destroy'])
                ->name('admin.user-sessions.destroy');
        });

    Route::middleware(['role:siswa'])
        ->prefix('siswa')
        ->group(function () {

            Route::get('/data', [SiswaController::class, 'indexDataSiswa'])
                ->name('siswa.data');

            Route::get('/guru', [GuruController::class, 'indexGuruSiswa'])
                ->name('siswa.guru');

            Route::get('/absensi/qr', [AbsensiController::class, 'showQr'])
                ->name('absensi.qr');

            Route::get('/absensi/data', [AbsensiController::class, 'indexSiswa'])
                ->name('siswa.absensi.data');

            Route::post('/absensi/data', [AbsensiController::class, 'indexSiswa'])
                ->name('siswa.absensi.data.filter');

            Route::get('/izin/ajukan', [IzinController::class, 'create'])
                ->name('izin.create');

            Route::post('/izin', [IzinController::class, 'store'])
                ->name('izin.store');
        });
});
