<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Property::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $properties = $query->paginate(5); // Adjust the pagination count as needed

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'currentRouteName' => Route::currentRouteName(),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return Inertia::render('Properties/Create',[
           'currentRouteName' => Route::currentRouteName(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('properties', 'public');
        }

        Property::create($data);

        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        //
    }

    public function edit(Property $property): Response
    {
        return Inertia::render('Properties/Edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        // Store the current image URL
        $imageUrl = $property->image_url;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($imageUrl) {
                Storage::disk('public')->delete($imageUrl);
            }

            // Store the new image in the 'properties' folder
            $imageUrl = $request->file('image')->store('properties', 'public');
            $validated['image_url'] = $imageUrl;
        }

        $property->update($validated);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }




    public function destroy(Property $property)
    {
        $property->delete();

    }




}
