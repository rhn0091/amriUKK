<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function showLoginForm()
    {
        return view('receptionist.auth.index');
    }
    public function login(Request $request)
    {
        $receptionist = \App\Models\Receptionist::where('email', $request->email)->first();

        if ($receptionist && $receptionist->password === $request->password) {
            Auth::guard('receptionist')->login($receptionist);
            return redirect()->route('receptionist.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }
    public function logout()
    {
        auth()->guard('receptionist')->logout();
        return redirect()->route('receptionist.auth.index');
    }

    public function dashboard()
    {
        return view('receptionist.dashboard');
    }
    public function reservations(Request $request)
    {
        $search = $request->input('search');
        $checkInDate = $request->input('check_in_date');

        $query = Reservation::with(['user', 'room']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($checkInDate) {
            $query->whereDate('check_in_date', $checkInDate);
        }

        $reservations = $query->orderBy('check_in_date', 'desc')->paginate(10);

        return view('receptionist.dashboard', compact('reservations', 'search', 'checkInDate'));
    }
}
