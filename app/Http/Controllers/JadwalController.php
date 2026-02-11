<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $hari = session('filter_hari', 'Senin');
        $ruanganId = session('filter_ruangan_id');

        $jadwals = Jadwal::with(['guru', 'mapel', 'ruangan', 'kelas'])
            ->where('hari', $hari);

        if ($ruanganId) {
            $jadwals->where('id_ruangan', $ruanganId);
        }

        $jadwals = $jadwals->orderBy('waktu_mulai')
            ->paginate(12)
            ->withQueryString();
        $ruangans = Ruangan::all();

        return view('admin.jadwal.index', compact('jadwals', 'ruangans'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_ruangan' => 'nullable|exists:ruangans,id_ruangan',
        ]);

        session(['filter_hari' => $request->hari, 'filter_ruangan_id' => $request->id_ruangan]);
        return redirect()->route('jadwal.index');
    }

    public function create()
    {
        $gurus = Guru::all();
        $mapels = Mapel::all();
        $ruangans = Ruangan::all();
        $kelases = Kelas::all();

        $hari = session('filter_hari', 'Senin');
        $ruanganId = session('filter_ruangan_id');

        return view('admin.jadwal.create', compact('gurus', 'mapels', 'ruangans', 'kelases', 'hari', 'ruanganId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke' => 'required|string|max:20',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
            'status' => 'required|in:Aktif,Istirahat',
        ]);

        $data = $request->only([
            'hari',
            'jam_ke',
            'waktu_mulai',
            'waktu_selesai',
            'id_ruangan',
            'status'
        ]);

        if ($request->status === 'Aktif') {
            $request->validate([
                'id_guru' => 'required|exists:gurus,id_guru',
                'id_mapel' => 'required|exists:mapels,id_mapel',
                'id_kelas' => 'required|exists:kelas,id_kelas',
            ]);
            $data['id_guru'] = $request->id_guru;
            $data['id_mapel'] = $request->id_mapel;
            $data['id_kelas'] = $request->id_kelas;
        } else {
            $data['id_guru'] = null;
            $data['id_mapel'] = null;
            $data['id_kelas'] = null;
        }

        Jadwal::create($data);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $gurus = Guru::all();
        $mapels = Mapel::all();
        $ruangans = Ruangan::all();
        $kelases = Kelas::all();
        return view('admin.jadwal.edit', compact('jadwal', 'gurus', 'mapels', 'ruangans', 'kelases'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'jam_ke' => 'required|string|max:20',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
            'status' => 'required|in:Aktif,Istirahat',
        ]);

        $data = $request->only([
            'jam_ke',
            'waktu_mulai',
            'waktu_selesai',
            'id_ruangan',
            'status'
        ]);

        if ($request->status === 'Aktif') {
            $request->validate([
                'id_guru' => 'required|exists:gurus,id_guru',
                'id_mapel' => 'required|exists:mapels,id_mapel',
                'id_kelas' => 'required|exists:kelas,id_kelas',
            ]);
            $data['id_guru'] = $request->id_guru;
            $data['id_mapel'] = $request->id_mapel;
            $data['id_kelas'] = $request->id_kelas;
        } else {
            $data['id_guru'] = null;
            $data['id_mapel'] = null;
            $data['id_kelas'] = null;
        }

        $jadwal->update($data);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
