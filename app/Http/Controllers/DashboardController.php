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
        $activeByRole = ['admin' => 0, 'guru' => 0, 'siswa' => 0];

        foreach ($activeSessions as $session) {
            if (isset($activeByRole[$session->user->role])) {
                $activeByRole[$session->user->role]++;
            }
        }

        $totalActive = array_sum($activeByRole);

        return view('admin.dashboard.admin', [
            'totalGuru' => Guru::count(),
            'totalSiswa' => Siswa::count(),
            'totalMapel' => Mapel::count(),
            'totalRuangan' => Ruangan::count(),
            'totalUser' => User::count(),
            'totalKelas' => Kelas::count(),
            'activeAdmin' => $activeByRole['admin'],
            'activeGuru'  => $activeByRole['guru'],
            'activeSiswa' => $activeByRole['siswa'],
            'totalActive' => $totalActive,
        ]);
    }

    private function showGuruDashboard(string $tanggal)
    {
        $guru = auth()->user()->userable;
        $kelasBinaan = Kelas::where('id_guru', $guru->id_guru)->first();
        $carbonTanggal = Carbon::parse($tanggal);

        $hariMap = [
            'monday' => 'senin',
            'tuesday' => 'selasa',
            'wednesday' => 'rabu',
            'thursday' => 'kamis',
            'friday' => 'jumat',
            'saturday' => 'sabtu',
            'sunday' => 'minggu',
        ];
        $hariIndo = $hariMap[strtolower($carbonTanggal->englishDayOfWeek)] ?? 'senin';
        $jamSekarang = now()->format('H:i:s');

        $jadwalSaatIni = null;
        if ($carbonTanggal->isToday()) {
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
                ->whereHas('siswa', fn($q) => $q->where('id_kelas', $kelasBinaan->id_guru))
                ->where('status', 'menunggu')
                ->get();
        }

        $totalHadir = $totalIzin = $totalSakit = $totalAlpa = 0;

        if ($kelasBinaan) {
            $hariIni = strtolower($carbonTanggal->englishDayOfWeek);
            if (!in_array($hariIni, ['saturday', 'sunday'])) {
                $siswaKelas = Siswa::where('id_kelas', $kelasBinaan->id_guru)->get();
                $tanggalHariIni = $carbonTanggal->format('Y-m-d');

                foreach ($siswaKelas as $siswa) {
                    $absen = Absensi::where('id_user', $siswa->id_siswa)
                        ->whereDate('created_at', $tanggalHariIni)
                        ->first();

                    if ($absen) {
                        $totalHadir++;
                    } else {
                        $izin = Izin::where('id_siswa', $siswa->id_siswa)
                            ->where('status', 'disetujui')
                            ->whereDate('tanggal_mulai', '<=', $tanggalHariIni)
                            ->whereDate('tanggal_selesai', '>=', $tanggalHariIni)
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

        $maxValue = max($totalHadir, $totalIzin, $totalSakit, $totalAlpa);
        if ($maxValue == 0) $maxValue = 1;

        return view('guru.dashboard.guru', [
            'guru' => $guru,
            'kelasBinaan' => $kelasBinaan,
            'jadwalSaatIni' => $jadwalSaatIni,
            'jadwalHariIni' => $jadwalHariIni,
            'izinMenunggu' => $izinMenunggu,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalSakit' => $totalSakit,
            'totalAlpa' => $totalAlpa,
            'maxKehadiran' => $maxValue,
            'tanggalSekarang' => $carbonTanggal,
        ]);
    }

    private function showSiswaDashboard(string $tanggal = null)
{
    $siswa = auth()->user()->userable;
    $kelas = $siswa->kelas;

    $carbonTanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();

    $absenHariIni = Absensi::where('id_user', $siswa->id_siswa)
        ->whereDate('created_at', $carbonTanggal->format('Y-m-d'))
        ->first();

    $izinAktif = Izin::where('id_siswa', $siswa->id_siswa)
        ->where('status', 'disetujui')
        ->whereDate('tanggal_mulai', '<=', $carbonTanggal->format('Y-m-d'))
        ->whereDate('tanggal_selesai', '>=', $carbonTanggal->format('Y-m-d'))
        ->first();

    $riwayat = [];
    $endDate = Carbon::now();
    $startDate = $endDate->copy()->subDays(6);

    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        $dayName = strtolower($date->englishDayOfWeek);
        if (in_array($dayName, ['saturday', 'sunday'])) continue;

        $tgl = $date->format('Y-m-d');
        $absen = Absensi::where('id_user', $siswa->id_siswa)
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
            } else {
                $status = 'Alpa';
            }
        }

        $riwayat[] = [
            'tanggal' => $date->copy(),
            'status' => $status,
            'is_today' => $date->isToday(),
        ];
    }

    return view('siswa.dashboard.siswa', compact(
        'siswa',
        'kelas',
        'absenHariIni',
        'izinAktif',
        'riwayat',
        'carbonTanggal'
    ));
}
}