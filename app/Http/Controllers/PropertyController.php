<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::where('is_published', true)->get();
        return Inertia::render('Properties/Index', [
            'properties' => $properties->toArray(),
            'currentRouteName' => Route::currentRouteName()
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
            'description' => 'required|string',
            'location' => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        Property::create($data);
        return redirect()->back()->with('success', 'Property added successfully.');
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

    public function update(Request $request, Property $property): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $property->update($request->all());

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }
}
