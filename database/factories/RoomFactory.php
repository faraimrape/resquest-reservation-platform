<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'property_id' => Property::factory(),
            'capacity' => $this->faker->numberBetween(1, 10),
            'price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'image_url' => $this->faker->imageUrl(640, 480, 'room', true),
        ];
    }
}
