<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
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

    public function create()
    {
        return view('admin.guru.create');
    }

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

    public function show(Guru $guru)
    {
        //
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id_guru . ',id_guru',
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

    public function destroy(Guru $guru)
    {
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus!');
    }
}
