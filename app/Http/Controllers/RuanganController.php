<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Guru;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $query = Ruangan::with('guru');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhereHas('guru', function ($guruQuery) use ($keyword) {
                        $guruQuery->where('nama', 'like', "%{$keyword}%");
                    });
            });
        }

        $ruangans = $query->latest()->paginate(10)->appends(['keyword' => $keyword]);

        return view('admin.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.ruangan.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:gurus,id_guru',
        ]);

        Ruangan::create($request->only(['nama', 'id_guru']));

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function show(Ruangan $ruangan)
    {
        //
    }

    public function edit(Ruangan $ruangan)
    {
        $gurus = Guru::all();
        return view('admin.ruangan.edit', compact('ruangan', 'gurus'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:gurus,id_guru',
        ]);

        $ruangan->update($request->only(['nama', 'id_guru']));

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diupdate!');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}
