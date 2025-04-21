<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class RoomFacilityController extends Controller
{
    public function index()
    {
        $facilities = RoomFacility::all();
        return view('user.room_facility.index', compact('facilities'));
    }
    public function AdminIndex()
    {
        $rooms = Room::with('facilities')->get();
        $facilities = RoomFacility::all();
        return view('admin.room_facility.index', compact('rooms','facilities'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('admin.room_facility.create',compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,rooms_id',
            'facility_name' => 'required|string',
        ]);

        RoomFacility::created([
            'rooms_id' => $request->rooms_id,
            'facility_name' => $request->facility_name,
        ]);
        
        return redirect()->route('admin.facility_index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(RoomFacility $roomFacility)
    {
        
    }

    public function edit(RoomFacility $roomFacility)
    {
        
    }

    public function update(Request $request, RoomFacility $roomFacility)
    {
        //
    }

    public function destroy(RoomFacility $roomFacility)
    {
        
    }
}
