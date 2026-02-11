<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{

    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        $mapels = $query->latest()->paginate(10);
        $mapels->appends($request->all());

        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:mapels,kode',
        ]);

        Mapel::create($request->only(['nama', 'kode']));

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function show(Mapel $mapel)
    {
        //
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:mapels,kode,' . $mapel->id_mapel . ',id_mapel',
        ]);

        $mapel->update($request->only(['nama', 'kode']));

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diupdate!');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
