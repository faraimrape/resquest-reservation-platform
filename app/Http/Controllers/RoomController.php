<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Retrieve the search filter, if any
        $search = $request->input('search');
        $query = Room::with('property');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('property', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
        }

        // Paginate the results
        $rooms = $query->paginate(5)
        ->appends($request->only('search'));

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
            'filters' => [
                'search' => $search,
            ],
            'currentRouteName' => Route::currentRouteName(),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $properties = Property::all();
        return Inertia::render('Rooms/Create',[
            'properties' => $properties,
            'currentRouteName' => Route::currentRouteName(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
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

    /**
     * @param Room $room
     * @return Response
     */
    public function edit(Room $room): Response
    {
        $properties = Property::all();
        return Inertia::render('Rooms/Edit', compact('room', 'properties'));
    }


    /**
     * @param Request $request
     * @param Room $room
     * @return RedirectResponse
     */
    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        $imageUrl = $room->image_url;

        if ($request->hasFile('image')) {
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

    /**
     * @param Room $room
     * @return RedirectResponse
     */
    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
