<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function showLoginForm()
    {
        return view('receptionist.auth.index');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->guard('receptionist')->attempt($credentials)) {
            return redirect()->route('receptionist.dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }
    public function logout()
    {
        auth()->guard('receptionist')->logout();
        return redirect()->route('receptionist.auth.index');
    }
}
