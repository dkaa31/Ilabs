<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    if ($role = $request->get('role')) {
        $query->where('role', $role);
    }

    $users = $query->latest()->paginate(10)->appends($request->only(['search', 'role']));

    return view('admin.user.index', compact('users'));
}

    public function create()
    {
        $gurus = Guru::whereDoesntHave('user')->get();
        $siswas = Siswa::whereDoesntHave('user')->get();
        return view('admin.user.create', compact('gurus', 'siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,guru,siswa',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($request->role === 'guru') {
            $request->validate(['id_guru' => 'required|exists:gurus,id_guru']);
            $guru = Guru::findOrFail($request->id_guru);
            $name = $guru->nama;
            $userableId = $guru->id_guru;
            $userableType = Guru::class;
        } elseif ($request->role === 'siswa') {
            $request->validate(['id_siswa' => 'required|exists:siswas,id_siswa']);
            $siswa = Siswa::findOrFail($request->id_siswa);
            $name = $siswa->nama;
            $userableId = $siswa->id_siswa;
            $userableType = Siswa::class;
        } else {
            $request->validate(['name' => 'required']);
            $name = $request->name;
            $userableId = null;
            $userableType = null;
        }

        User::create([
            'nama' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'userable_id' => $userableId,
            'userable_type' => $userableType,
        ]);

        return redirect()->route('user.index')->with('success', 'Akun berhasil dibuat!');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email' ->ignore($user->id_user, 'id_user'),
        ]);

        $data = ['email' => $request->email];
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('user.index')->with('success', 'Akun berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id_user === auth()->user()->id_user) {
            return back()->withErrors(['error' => 'Tidak bisa menghapus akun sendiri.']);
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Akun berhasil dihapus!');
    }
}