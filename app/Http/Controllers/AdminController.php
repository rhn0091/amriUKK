<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.index');
    }
    public function login(Request $request)
    {
        $admin = \App\Models\Admin::where('email', $request->email)->first();

        if ($admin && $admin->password === $request->password) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau password salah.');
    }
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.auth.index');
    }
}
