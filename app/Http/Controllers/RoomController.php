<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('property')->get();
        return Inertia::render('Rooms/Index', compact('rooms'));
    }

    public function create()
    {
        $properties = Property::all(); // Fetch all properties for the dropdown
        return Inertia::render('Rooms/Create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Room added successfully.');
    }

    public function edit(Room $room)
    {
        $properties = Property::all();
        return Inertia::render('Rooms/Edit', compact('room', 'properties'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
