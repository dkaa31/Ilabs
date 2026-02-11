<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function create()
    {
        return view('siswa.izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:sakit,izin,dispen',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'alasan' => 'nullable|string',
            'file_surat' => 'required_if:jenis,sakit,dispen|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_surat.required_if' => 'Surat wajib diupload untuk izin sakit/dispen.',
        ]);

        $tanggalSelesai = $request->tanggal_selesai ?? $request->tanggal_mulai;

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_izin', 'public');
        }

        $siswaId = Auth::user()->userable->id_siswa;

        $cek = Izin::where('id_siswa', $siswaId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereDate('tanggal_mulai', '<=', $request->tanggal_mulai)
            ->whereDate('tanggal_selesai', '>=', $request->tanggal_mulai)
            ->exists();

        if ($cek) {
            return back()->withErrors([
                'tanggal_mulai' => 'Kamu sudah memiliki izin di tanggal tersebut.'
            ]);
        }


        Izin::create([
            'id_siswa' => Auth::user()->userable->id_siswa,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $tanggalSelesai,
            'alasan' => $request->alasan,
            'file_surat' => $filePath,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('izin.create')
            ->with('success', 'Pengajuan izin berhasil dikirim!');
    }



    public function index()
    {
        $guruId = Auth::user()->userable->id_guru;

        $kelasIds = Kelas::where('id_guru', $guruId)->pluck('id_kelas');

        if ($kelasIds->isEmpty()) {
            $pengajuan = collect();
        } else {
            $siswaIds = Siswa::whereIn('id_kelas', $kelasIds)->pluck('id_siswa');

            $pengajuan = Izin::with('siswa.kelas')
                ->whereIn('id_siswa', $siswaIds)
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('guru.izin.index', compact('pengajuan'));
    }

    public function approve(Izin $izin)
    {
        $kelas = $izin->siswa->kelas;

        if (!$kelas || $kelas->id_guru !== Auth::user()->userable->id_guru) {
            abort(403, 'Anda bukan wali kelas siswa ini.');
        }

        $izin->update([
            'status' => 'disetujui',
            'diproses_oleh' => Auth::id(),
        ]);

        return back()->with('success', 'Izin berhasil disetujui.');
    }

    public function reject(Izin $izin)
    {
        $kelas = $izin->siswa->kelas;

        if (!$kelas || $kelas->id_guru !== Auth::user()->userable->id_guru) {
            abort(403, 'Anda bukan wali kelas siswa ini.');
        }

        $izin->update([
            'status' => 'ditolak',
            'diproses_oleh' => Auth::id(),
        ]);

        return back()->with('success', 'Izin berhasil ditolak.');
    }
}
