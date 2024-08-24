<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validating the image
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            // Store the image in the 'rooms' folder
            $imageUrl = $request->file('image')->store('rooms', 'public');
        }

        Room::create([
            'name' => $request->name,
            'property_id' => $request->property_id,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'image_url' => $imageUrl,
        ]);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validating the image
        ]);

        $imageUrl = $room->image_url;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($imageUrl) {
                Storage::disk('public')->delete($imageUrl);
            }

            // Store the new image in the 'rooms' folder
            $imageUrl = $request->file('image')->store('rooms', 'public');
        }

        $room->update([
            'name' => $request->name,
            'property_id' => $request->property_id,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
