<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with('room.property','guests')->get();
        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations->toArray(),
            'currentRouteName' => Route::currentRouteName()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Reservations/Create');
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
    public function edit(Reservation $reservation)
    {
        return Inertia::render('Reservations/Edit', compact('reservation'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $reservation->update($data);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    public function approve(Reservation $reservation)
    {
        $reservation->update(['is_approved' => true]);

        // Trigger email notification if needed

        return redirect()->route('reservations.index')->with('success', 'Reservation approved.');
    }


    public function reject(Reservation $reservation)
    {
        $reservation->update(['is_approved' => false]);

        // Trigger email notification if needed

        return redirect()->route('reservations.index')->with('success', 'Reservation rejected.');
    }

}
