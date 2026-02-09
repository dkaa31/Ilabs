<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Guru::query();

    if ($request->filled('keyword')) {
        $query->where('nama', 'like', '%' . $request->keyword . '%')
              ->orWhere('nip', 'like', '%' . $request->keyword . '%');
    }

    $gurus = $query
    ->orderBy('nama', 'asc')
    ->paginate(10)
    ->withQueryString();


    return view('admin.guru.index', compact('gurus'));
}


public function indexGuruSiswa(Request $request)
{
    $query = Guru::query();

    if ($request->filled('keyword')) {
        $query->where('nama', 'like', '%' . $request->keyword . '%')
              ->orWhere('nip', 'like', '%' . $request->keyword . '%');
    }

    $gurus = $query
    ->orderBy('nama', 'asc')
    ->paginate(10)
    ->withQueryString();


    return view('siswa.guru.index', compact('gurus'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3048',
        ]);

        $data = $request->only(['nama', 'nip']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('guru', 'public');
            $data['foto'] = $path;
        }

        Guru::create($data);

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3048',
        ]);

        $data = $request->only(['nama', 'nip']);

        if ($request->hasFile('foto')) {
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }
            $path = $request->file('foto')->store('guru', 'public');
            $data['foto'] = $path;
        }

        $guru->update($data);

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus!');
    }
}
