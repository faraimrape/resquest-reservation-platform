<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the search filter, if any
        $search = $request->input('search');

        // Build the query with filtering and eager loading
        $query = Room::with('property');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('property', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
        }

        // Paginate the results
        $rooms = $query->paginate(5) // Adjust the number per page as needed
        ->appends($request->only('search')); // Append the search query to pagination links

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
            'filters' => [
                'search' => $search,
            ],
            'currentRouteName' => Route::currentRouteName(),
        ]);
    }

    public function create()
    {
        $properties = Property::all(); // Fetch all properties for the dropdown
        return Inertia::render('Rooms/Create',[
            'properties' => $properties,
            'currentRouteName' => Route::currentRouteName(),
        ]);
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
