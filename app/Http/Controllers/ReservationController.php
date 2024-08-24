<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Property;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve the search filter, if any
        $search = $request->input('search');

        // Build the query with filtering and eager loading
        $query = Reservation::with('room.property', 'guests');

        if ($search) {
            $query->whereHas('guests', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            })->orWhereHas('room', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('room.property', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        // Paginate the results
        $reservations = $query->paginate(5)->appends($request->only('search'));

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
            'filters' => [
                'search' => $search,
            ],
            'currentRouteName' => Route::currentRouteName(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $properties = Property::with('rooms')->get();

        return Inertia::render('Reservations/Create', [
            'properties' => $properties,
            'currentRouteName' => Route::currentRouteName()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guest_details' => 'required|array',
        ]);

        $reservation = Reservation::create([
            'room_id' => $data['room_id'],
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'is_approved' => false,
        ]);

        foreach ($data['guest_details'] as $guestData) {
            Guest::create([
                'reservation_id' => $reservation->id,
                'first_name' => $guestData['first_name'],
                'last_name' => $guestData['last_name'],
                'email' => $guestData['email'],
                'phone' => $guestData['phone'],
            ]);
        }

        // Trigger email notification if needed

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Update the specified resource in storage.
     */

    public function edit(Reservation $reservation): Response
    {
        $properties = Property::with('rooms')->get();
        $rooms = Room::all();

        return Inertia::render('Reservations/Edit', [
            'reservation' => $reservation->load('room.property', 'guests'),
            'properties' => $properties,
            'rooms' => $rooms,
        ]);
    }


    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'is_approved' => 'required|boolean',
        ]);

        $reservation->update($validated);
        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    public function approve(Reservation $reservation): RedirectResponse
    {
        $reservation->update(['is_approved' => true]);
        // Trigger email notification if needed
        return redirect()->route('reservations.index')->with('success', 'Reservation approved.');
    }


    public function reject(Reservation $reservation): RedirectResponse
    {
        $reservation->update(['is_approved' => false]);
        // Trigger email notification if needed
        return redirect()->route('reservations.index')->with('success', 'Reservation rejected.');
    }


}
