<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Kelas::with('waliKelas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('waliKelas', function ($guruQuery) use ($search) {
                        $guruQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $kelas = $query->latest()->paginate(10)->appends(['search' => $search]);

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:gurus,id_guru',
        ]);

        Kelas::create($request->only(['nama', 'id_guru']));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(Kelas $kelas)
    {
        //
    }

    public function edit(Kelas $kelas)
    {
        $gurus = Guru::all();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:gurus,id_guru',
        ]);

        $kelas->update($request->only(['nama', 'id_guru']));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
