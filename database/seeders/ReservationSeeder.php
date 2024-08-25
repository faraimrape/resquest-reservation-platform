<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        // Sample reservation seeder
        $room = Room::first();

        Reservation::create([
            'room_id' => $room->id,
            'check_in' => now()->addDays(3),
            'check_out' => now()->addDays(7),
            'is_approved' => false,
        ]);
    }
}
