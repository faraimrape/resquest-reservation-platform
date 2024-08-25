<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Retrieve the search filter, if any
        $search = $request->input('search');
        $query = Guest::with('reservation.room.property');

        if ($search) {
            $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhereHas('reservation.room', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('reservation.room.property', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
        }

        // Paginate the results
        $guests = $query->paginate(5)->appends($request->only('search'));

        return Inertia::render('Guests/Index', [
            'guests' => $guests,
            'filters' => [
                'search' => $search,
            ],
            'currentRouteName' => Route::currentRouteName(),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest): Response
    {
        return Inertia::render('Guests/Edit', compact('guest'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $guest->update($data);

        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest): RedirectResponse
    {
        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest deleted successfully.');
    }
}
