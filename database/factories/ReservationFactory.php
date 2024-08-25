<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'check_in' => $this->faker->dateTimeBetween('now', '+1 week'),
            'check_out' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'is_approved' => $this->faker->boolean,
        ];
    }
}
