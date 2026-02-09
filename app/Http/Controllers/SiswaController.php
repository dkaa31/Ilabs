<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $kelasList = Kelas::all();

    $siswas = Siswa::with('kelas')
        ->when($request->id_kelas, function ($query) use ($request) {
            $query->where('id_kelas', $request->id_kelas);
        })
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        })
        ->orderBy('nama', 'asc')
        ->paginate(10)
        ->withQueryString();

    return view('admin.siswa.index', compact('siswas','kelasList'));
}


    public function indexSiswa(Request $request)
{
    $kelasList = Kelas::all();

    $siswas = Siswa::with('kelas')
        ->when($request->id_kelas, function ($query) use ($request) {
            $query->where('id_kelas', $request->id_kelas);
        })
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        })
        ->orderBy('nama', 'asc')
        ->paginate(10)
        ->withQueryString();

    return view('guru.siswa.index', compact('siswas','kelasList'));
}


    public function indexDataSiswa(Request $request)
{
    $kelasList = Kelas::all();

    $siswas = Siswa::with('kelas')
        ->when($request->id_kelas, function ($query) use ($request) {
            $query->where('id_kelas', $request->id_kelas);
        })
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        })
        ->orderBy('nama', 'asc')
        ->paginate(10)
        ->withQueryString();

    return view('siswa.siswa.index', compact('siswas','kelasList'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis',
            'nisn' => 'required|string|unique:siswas,nisn',
            'nama' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        Siswa::create($request->only(['nis', 'nisn', 'nama', 'id_kelas']));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis,' . $siswa->id,
            'nisn' => 'required|string|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $siswa->update($request->only(['nis', 'nisn', 'nama', 'id_kelas']));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}
