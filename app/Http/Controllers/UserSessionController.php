<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSession::with('user');

        if ($request->filled('keyword')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        $activeSessions = $query
            ->orderBy('last_activity', 'desc')
            ->get();

        return view('admin.user-sessions.index', compact('activeSessions'));
    }


    public function destroy(UserSession $session)
    {
        $sessionFile = storage_path('framework/sessions/' . $session->id_user_session);
        if (File::exists($sessionFile)) {
            File::delete($sessionFile);
        }

        $session->delete();

        return redirect()->back()->with('success', 'User berhasil di-logout!');
    }
}
