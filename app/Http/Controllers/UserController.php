<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->is_profile_complete) {
            return redirect()->route('user.profile');
        }

        return view('user.dashboard');
    }

    public function showProfileForm()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nik' => 'required|string|max:16',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'username' => 'required|string|unique:users,username,' . Auth::id(),
        ]);

        $user = Auth::user();

        if ($user instanceof User) {
        $user->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'username' => $request->username,
            'is_profile_complete' => true,
        ]);
    }
        return redirect()->route('user.dashboard');
    }
}
