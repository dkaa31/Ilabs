<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function selectRoomForSlide()
    {
        $ruangans = Ruangan::with('guru')->get();
        return view('display.select-room', compact('ruangans'));
    }

    public function selectRoomForSchedule()
    {
        $ruangans = Ruangan::with('guru')->get();
        return view('display.select-room', compact('ruangans'));
    }

    public function showSlide($ruanganId)
    {
        $ruangan = Ruangan::with('guru')->findOrFail($ruanganId);
        
        $now = now()->timezone('Asia/Jakarta');
        $hariIni = $now->format('l');
        
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
        ];
        $hariIndo = $hariMap[$hariIni] ?? 'Senin';

        // 🔥 Tambahkan relasi 'kelas'
        $jadwalHariIni = Jadwal::with(['guru', 'mapel', 'kelas'])
            ->where('id_ruangan', $ruangan->id_ruangan)
            ->where('hari', $hariIndo)
            ->orderBy('waktu_mulai')
            ->get();

        $jadwalSekarang = null;
        $jamSekarang = $now->format('H:i');

        foreach ($jadwalHariIni as $j) {
            $mulai = \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i');
            $selesai = \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i');
            
            if ($jamSekarang >= $mulai && $jamSekarang < $selesai) {
                $jadwalSekarang = $j;
                break;
            }
        }

        return view('display.slide', compact('ruangan', 'jadwalSekarang'));
    }

    public function showSchedule($ruanganId)
    {
        $ruangan = Ruangan::with('guru')->findOrFail($ruanganId);
        return view('display.schedule', compact('ruangan'));
    }

    public function showDay($ruanganId, $hari)
    {
        $hari = ucfirst(strtolower($hari));
        if (!in_array($hari, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'])) {
            abort(404);
        }

        $ruangan = Ruangan::with('guru')->findOrFail($ruanganId);
        
        // 🔥 Tambahkan relasi 'kelas'
        $jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])
            ->where('id_ruangan', $ruangan->id_ruangan)
            ->where('hari', $hari)
            ->orderBy('waktu_mulai')
            ->get();

        return view('display.hari', compact('ruangan', 'jadwals', 'hari'));
    }
}