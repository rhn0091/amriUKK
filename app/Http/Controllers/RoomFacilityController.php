<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomFacility;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomFacilityController extends Controller
{
    public function index()
    {
        $facilities = RoomFacility::all();
        return view('admin.room_facility.index', compact('facilities'));
    }


    public function adminIndex()
    {
        $rooms = Room::with('facilities')->get();
        $facilities = RoomFacility::all();
        return view('admin.room_facility.index', compact('rooms', 'facilities'));
    }


    public function create(Request $request)
    {
        $rooms = Room::all();
        $selectedRoomId = $request->query('rooms_id');
        return view('admin.room_facility.create', compact('rooms' , 'selectedRoomId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,rooms_id',
            'facility_name' => 'required|string',
        ]);

        RoomFacility::create([
            'rooms_id' => $request->rooms_id,
            'facility_name' => $request->facility_name
        ]);

        return redirect()->route('admin.room_facility.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $facility = RoomFacility::findOrFail($id);
        $rooms = Room::all();
        return view('admin.room_facility.update', compact('facility', 'rooms'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,rooms_id',
            'facility_name' => 'required|string',
        ]);

        $facility = RoomFacility::findOrFail($id);

        $facility->update([
            'rooms_id' => $request->rooms_id,
            'facility_name' => $request->facility_name
        ]);

        return redirect()->route('admin.room_facility.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }


    public function show($id)
    {
        $room = Room::with('facilities', 'images')->findOrFail($id);
        $facilities = $room->facilities;
        return view('user.show', compact('room', 'facilities'));
    }

    public function destroy($id)
    {
        $facility = RoomFacility::findOrFail($id);
        $roomId = $facility->rooms_id;
        $facility->delete();

        return redirect()->route('admin.room_facility.index', $roomId)->with('success', 'Fasilitas berhasil dihapus.');
    }
}
