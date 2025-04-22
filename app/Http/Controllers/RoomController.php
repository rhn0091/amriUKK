<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(5)->withQueryString();
        return view('user.index', compact('rooms'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function adminIndex()
    {
        $rooms = Room::select('rooms_id', 'room_type', DB::raw('SUM(total_room) as total_room'))
            ->groupBy('rooms_id', 'room_type')
            ->get();

        return view('admin.dashboard', compact('rooms'));
    }

    public function RecepsionistIndex()
    {
        $rooms = Room::latest()->paginate(5)->withQueryString();
        return view('receptionist.kamar', compact('rooms'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('admin.room.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_room' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room = Room::create([
            'rooms_id' => \Illuminate\Support\Str::uuid(),
            'room_type' => $request->room_type,
            'price' => $request->price,
            'total_room' => $request->total_room,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'image' => $request->file('image') ? $request->file('image')->store('room_images', 'public') : null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('rooms', 'public');

                RoomImage::create([
                    'rooms_id' => $room->rooms_id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.room.create')->with('success', 'Room created successfully!');
    }
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.room.edit', compact('room'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_room' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room = Room::findOrFail($id);
        $room->update([
            'room_type' => $request->room_type,
            'price' => $request->price,
            'total_room' => $request->total_room,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'image' => $request->file('image') ? $request->file('image')->store('room_images', 'public') : null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Room updated successfully!');
    }
    public function destroy($room_id)
{
    $room = Room::findOrFail($room_id);

    if ($room->reservations()->exists()) {  
        return redirect()->back()->with('error', 'Room tidak bisa dihapus karena masih ada pesanan.');
    }

    $room->delete();

    return redirect()->route('admin.room.index')->with('success', 'Room berhasil dihapus!');
}

    public function show($id)
    {
        $room = Room::with(['images'])->findOrFail($id);
        return view('user.show', compact('room'));
    }

    public function Adminshow($id)
    {
        $room = Room::with(['images'])->findOrFail($id);
        return view('admin.room.show', compact('room'));
    }
}
