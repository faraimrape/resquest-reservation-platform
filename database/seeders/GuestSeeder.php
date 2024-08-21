<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run()
    {
        // Sample guest seeder
        $reservation = Reservation::first();

        Guest::create([
            'reservation_id' => $reservation->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'phone' => '555-1234',
        ]);
    }
}
