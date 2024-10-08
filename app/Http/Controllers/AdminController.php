<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::user(); // Mendapatkan admin yang sedang login
        
        // Hanya untuk pengecekan, jika admin juga memiliki data profile yang perlu dilengkapi
        if (!$admin->is_profile_complete) {
            return redirect()->route('user.profile.form')
                             ->with('warning', 'Anda harus melengkapi data diri terlebih dahulu.');
        }

        // Jika admin sudah melengkapi profile, tampilkan dashboard
        return view('admin.dashboard', ['admin' => $admin]);
    }
}
