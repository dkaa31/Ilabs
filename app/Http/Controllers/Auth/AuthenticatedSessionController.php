<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\UserSession;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = auth()->user();
    $ip = $request->ip();

    $sessionLifetime = config('session.lifetime') * 60;

    // 🔒 Cek sesi aktif di IP yang sama
    $existingSession = UserSession::where('ip_address', $ip)
        ->where('last_activity', '>=', time() - $sessionLifetime)
        ->first();

    if ($existingSession) {
        Auth::logout();

        return back()->withErrors([
            'email' => 'Perangkat ini sedang digunakan untuk login. Silakan logout terlebih dahulu.'
        ]);
    }

    $request->session()->regenerate();

    UserSession::create([
        'id_user'       => $user->id_user,
        'session_id'    => $request->session()->getId(),
        'ip_address'    => $ip,
        'user_agent'    => $request->userAgent(),
        'last_activity' => time(),
    ]);

    return match ($user->role) {
        'admin' => redirect()->intended(route('admin.dashboard')),
        'guru'  => redirect()->intended(route('guru.dashboard')),
        'siswa' => redirect()->intended(route('siswa.dashboard')),
        default => redirect('/'),
    };
}

public function destroy(Request $request)
{
    UserSession::where('id_user', auth()->user()->id_user)->delete();

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}