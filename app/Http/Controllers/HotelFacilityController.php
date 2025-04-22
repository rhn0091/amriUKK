<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HotelFacility;

class HotelFacilityController extends Controller
{
    public function index()
    {
        $hotelFacilitys = HotelFacility::all();
        return view('user.hotel_facility.index', compact('hotelFacilitys'));
    }
    public function adminIndex()
    {
        $hotelFacilitys = HotelFacility::all();
        return view('admin.hotel_facility.index', compact('hotelFacilitys'));
    }


    public function create()
    {
        return view('admin.hotel_facility.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        HotelFacility::create([
            'id' => Str::uuid(),
            'facility_name' => $request->facility_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.hotel_facility.index')->with('success', 'fasilitas berhasil di tambahkan');
    }


    public function edit($id)
    {
        $facility = HotelFacility::findOrFail($id);
        return view('admin.hotel_facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $facility = HotelFacility::findOrFail($id);
        $facility->update($request->only('facility_name', 'description'));

        return redirect()->route('admin.hotel_facilities.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $hotelFacility = HotelFacility::findOrFail($id);
        $hotelFacility->delete();

        return redirect()->route('admin.hotel_facility.index');
    }
}
