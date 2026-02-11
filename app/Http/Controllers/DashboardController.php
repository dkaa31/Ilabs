<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Izin;
use App\Models\Absensi;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal', now()->format('Y-m-d'));

        if (!Carbon::hasFormat($tanggal, 'Y-m-d')) {
            $tanggal = now()->format('Y-m-d');
        }

        $user = Auth::user();

        return match ($user->role) {
            'admin' => $this->showAdminDashboard(),
            'guru'  => $this->showGuruDashboard($tanggal),
            'siswa' => $this->showSiswaDashboard($tanggal),
            default => redirect('/'),
        };
    }

    private function showAdminDashboard()
    {
        $activeSessions = UserSession::with('user')->get();

        $activeByRole = [
            'admin' => 0,
            'guru' => 0,
            'siswa' => 0
        ];

        foreach ($activeSessions as $session) {
            if ($session->user && isset($activeByRole[$session->user->role])) {
                $activeByRole[$session->user->role]++;
            }
        }

        return view('admin.dashboard.admin', [
            'totalGuru' => Guru::count(),
            'totalSiswa' => Siswa::count(),
            'totalMapel' => Mapel::count(),
            'totalRuangan' => Ruangan::count(),
            'totalUser' => User::count(),
            'totalKelas' => Kelas::count(),
            'activeAdmin' => $activeByRole['admin'],
            'activeGuru' => $activeByRole['guru'],
            'activeSiswa' => $activeByRole['siswa'],
            'totalActive' => array_sum($activeByRole),
        ]);
    }

    private function showGuruDashboard(string $tanggal)
    {
        $guru = Auth::user()->userable;
        $kelasBinaan = Kelas::where('id_guru', $guru->id_guru)->first();
        $tanggalSekarang = Carbon::parse($tanggal);

        $hariMap = [
            'monday' => 'senin',
            'tuesday' => 'selasa',
            'wednesday' => 'rabu',
            'thursday' => 'kamis',
            'friday' => 'jumat',
            'saturday' => 'sabtu',
            'sunday' => 'minggu',
        ];

        $hariIndo = $hariMap[strtolower($tanggalSekarang->englishDayOfWeek)];
        $jamSekarang = now()->format('H:i:s');

        $jadwalSaatIni = null;

        if ($tanggalSekarang->isToday()) {
            $jadwalSaatIni = Jadwal::with(['kelas', 'mapel', 'ruangan'])
                ->where('id_guru', $guru->id_guru)
                ->where('hari', $hariIndo)
                ->where('waktu_mulai', '<=', $jamSekarang)
                ->where('waktu_selesai', '>=', $jamSekarang)
                ->first();
        }

        $jadwalHariIni = Jadwal::with(['kelas', 'mapel', 'ruangan'])
            ->where('id_guru', $guru->id_guru)
            ->where('hari', $hariIndo)
            ->orderBy('waktu_mulai')
            ->get();

        $izinMenunggu = collect();

        if ($kelasBinaan) {
            $izinMenunggu = Izin::with('siswa')
                ->whereHas('siswa', function ($q) use ($kelasBinaan) {
                    $q->where('id_kelas', $kelasBinaan->id_kelas);
                })
                ->where('status', 'menunggu')
                ->get();
        }

        $totalHadir = 0;
        $totalIzin = 0;
        $totalSakit = 0;
        $totalAlpa = 0;

        if ($kelasBinaan) {
            if (!in_array(strtolower($tanggalSekarang->englishDayOfWeek), ['saturday', 'sunday'])) {

                $siswaKelas = Siswa::where('id_kelas', $kelasBinaan->id_kelas)->get();
                $tgl = $tanggalSekarang->format('Y-m-d');

                foreach ($siswaKelas as $siswa) {

                    $absen = Absensi::where('id_user', $siswa->id_siswa)
                        ->whereDate('created_at', $tgl)
                        ->first();

                    if ($absen) {
                        $totalHadir++;
                    } else {

                        $izin = Izin::where('id_siswa', $siswa->id_siswa)
                            ->where('status', 'disetujui')
                            ->whereDate('tanggal_mulai', '<=', $tgl)
                            ->whereDate('tanggal_selesai', '>=', $tgl)
                            ->first();

                        if ($izin) {
                            if ($izin->jenis === 'sakit') {
                                $totalSakit++;
                            } else {
                                $totalIzin++;
                            }
                        } else {
                            $totalAlpa++;
                        }
                    }
                }
            }
        }

        $maxKehadiran = max($totalHadir, $totalIzin, $totalSakit, $totalAlpa, 1);

        return view('guru.dashboard.guru', compact(
            'guru',
            'kelasBinaan',
            'tanggalSekarang',
            'jadwalSaatIni',
            'jadwalHariIni',
            'izinMenunggu',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'maxKehadiran'
        ));
    }

    private function showSiswaDashboard(string $tanggal)
{
    $user = Auth::user();
    $siswa = $user->userable;
    $kelas = $siswa?->kelas;

    $tanggalSekarang = Carbon::parse($tanggal);

    $absenHariIni = Absensi::where('id_user', $user->id_user)
        ->whereDate('created_at', $tanggalSekarang)
        ->first();

    $izinAktif = Izin::where('id_siswa', $siswa->id_siswa)
        ->where('status', 'disetujui')
        ->whereDate('tanggal_mulai', '<=', $tanggalSekarang)
        ->whereDate('tanggal_selesai', '>=', $tanggalSekarang)
        ->first();

    $riwayat = collect();

    for ($i = 0; $i < 7; $i++) {

        $tgl = now()->subDays($i);
        $status = 'Alpa';

        $absen = Absensi::where('id_user', $user->id_user)
            ->whereDate('created_at', $tgl)
            ->first();

        if ($absen) {
            $status = 'Hadir';
        } else {
            $izin = Izin::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $tgl)
                ->whereDate('tanggal_selesai', '>=', $tgl)
                ->first();

            if ($izin) {
                $status = ucfirst($izin->jenis);
            }
        }

        $riwayat->push([
            'tanggal' => $tgl,
            'status' => $status,
            'is_today' => $tgl->isToday()
        ]);
    }

    return view('siswa.dashboard.siswa', compact(
        'siswa',
        'kelas',
        'absenHariIni',
        'izinAktif',
        'riwayat'
    ));
}

}
