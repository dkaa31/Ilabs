<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function showQr()
    {
        $user = Auth::user();

        Absensi::where('id_user', $user->id_user)
            ->where('expires_at', '<', now())
            ->delete();

        $absensi = Absensi::where('id_user', $user->id_user)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$absensi) {
            $absensi = Absensi::create([
                'id_user' => $user->id_user,
                'token' => Str::random(32),
                'expires_at' => now()->addSeconds(10),
                'used' => false,
            ]);
        }

        $qrUrl = url("/absensi/verify/{$absensi->token}");

        return view('siswa.absensi.qr', compact('qrUrl', 'absensi'));
    }

    public function scan()
    {
        return view('guru.absensi.scan');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $absensi = Absensi::with('user')
            ->where('token', $request->token)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak valid / sudah kadaluarsa'
            ]);
        }

        $absensi->update([
            'used' => true,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'user' => $absensi->user->nama,
            'kelas' => $absensi->user->kelas ?? '-',
            'jam' => now()->format('H:i:s'),
        ]);
    }

public function index(Request $request)
{
    $kelass = \App\Models\Kelas::pluck('nama');

    $selectedKelas   = $request->kelas;
    $selectedTanggal = $request->filled('tanggal')
        ? Carbon::parse($request->tanggal)->format('Y-m-d')
        : now()->format('Y-m-d');

    $search = $request->search;
    $status = $request->status;

    $hasil = collect();

    if ($selectedKelas) {

        $kelas = \App\Models\Kelas::where('nama', $selectedKelas)->first();

        if ($kelas) {

            $siswas = \App\Models\Siswa::with(['kelas','user'])
                ->where('id_kelas', $kelas->id_kelas)
                ->when($search, function($q) use ($search){
                    $q->where('nama','like','%'.$search.'%');
                })
                ->get();

            $absenUserIds = Absensi::whereDate('updated_at', $selectedTanggal)
                ->where('used', true)
                ->pluck('id_user')
                ->toArray();

            $hasil = $siswas->map(function ($siswa) use ($absenUserIds, $selectedTanggal) {

                $userId = $siswa->user?->id_user;
                $sudahAbsen = $userId && in_array($userId, $absenUserIds);

                // cek izin disetujui
                $izin = \App\Models\Izin::where('siswa_id', $siswa->id_siswa)
                    ->where('status','disetujui')
                    ->whereDate('tanggal_mulai','<=',$selectedTanggal)
                    ->where(function($q) use ($selectedTanggal){
                        $q->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai','>=',$selectedTanggal);
                    })
                    ->first();

                if ($izin) {
                    $statusTampil = ucfirst($izin->jenis);
                    $jam = '-';
                } elseif ($sudahAbsen) {
                    $statusTampil = 'Sudah Absen';
                    $jam = Absensi::where('id_user',$userId)
                        ->whereDate('updated_at',$selectedTanggal)
                        ->first()?->updated_at?->format('H:i:s');
                } else {
                    $statusTampil = 'Belum Absen';
                    $jam = '-';
                }

                return [
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas->nama,
                    'hari' => Carbon::parse($selectedTanggal)->translatedFormat('l'),
                    'tanggal' => $selectedTanggal,
                    'jam_absen' => $jam,
                    'status_tampil' => $statusTampil
                ];
            });

            if ($status) {
                $hasil = $hasil->filter(function($item) use ($status){
                    return match($status) {
                        'hadir' => $item['status_tampil'] == 'Sudah Absen',
                        'izin'  => $item['status_tampil'] == 'Izin',
                        'sakit' => $item['status_tampil'] == 'Sakit',
                        'belum' => $item['status_tampil'] == 'Belum Absen',
                        default => true
                    };
                });
            }
        }
    }

    return view('guru.absensi.data', compact(
        'kelass',
        'hasil',
        'selectedKelas',
        'selectedTanggal'
    ));
}


public function indexSiswa(Request $request)
{
    $kelass = \App\Models\Kelas::pluck('nama');

    $selectedKelas = $request->kelas;

    $selectedTanggal = $request->filled('tanggal')
        ? Carbon::parse($request->tanggal)->format('Y-m-d')
        : now()->format('Y-m-d');

    $search = $request->search;
    $status = $request->status;

    $hasil = collect();

    if ($selectedKelas) {

        $kelas = \App\Models\Kelas::where('nama', $selectedKelas)->first();

        if ($kelas) {

            $siswas = \App\Models\Siswa::with(['kelas','user'])
                ->where('id_kelas', $kelas->id_kelas)
                ->when($search, function($q) use ($search){
                    $q->where('nama','like','%'.$search.'%');
                })
                ->get();

            $absenUserIds = Absensi::whereDate('updated_at', $selectedTanggal)
                ->where('used', true)
                ->pluck('id_user')
                ->toArray();

            $hasil = $siswas->map(function ($siswa) use ($absenUserIds, $selectedTanggal) {

                $userId = $siswa->user?->id_user;
                $sudahAbsen = $userId && in_array($userId, $absenUserIds);

                // cek izin disetujui
                $izin = \App\Models\Izin::where('id_siswa', $siswa->id_siswa)
                    ->where('status','disetujui')
                    ->whereDate('tanggal_mulai','<=',$selectedTanggal)
                    ->where(function($q) use ($selectedTanggal){
                        $q->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai','>=',$selectedTanggal);
                    })
                    ->first();

                if ($izin) {
                    $statusTampil = ucfirst($izin->jenis);
                    $jam = '-';
                } elseif ($sudahAbsen) {
                    $statusTampil = 'Sudah Absen';
                    $jam = Absensi::where('id_user',$userId)
                        ->whereDate('updated_at',$selectedTanggal)
                        ->first()?->updated_at?->format('H:i:s');
                } else {
                    $statusTampil = 'Belum Absen';
                    $jam = '-';
                }

                return [
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas->nama,
                    'hari' => Carbon::parse($selectedTanggal)->translatedFormat('l'),
                    'tanggal' => $selectedTanggal,
                    'jam_absen' => $jam,
                    'status_tampil' => $statusTampil
                ];
            });

            if ($status) {
                $hasil = $hasil->filter(function($item) use ($status){
                    return match($status) {
                        'hadir' => $item['status_tampil'] == 'Sudah Absen',
                        'izin'  => $item['status_tampil'] == 'Izin',
                        'sakit' => $item['status_tampil'] == 'Sakit',
                        'belum' => $item['status_tampil'] == 'Belum Absen',
                        default => true
                    };
                });
            }

        }
    }

    return view('siswa.absensi.data', compact(
        'kelass',
        'hasil',
        'selectedKelas',
        'selectedTanggal'
    ));
}

}
