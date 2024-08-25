<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'location' => $this->faker->address,
            'description' => $this->faker->paragraph,
            'is_published' => $this->faker->boolean,
            'image_url' => $this->faker->imageUrl(640, 480, 'property', true),
        ];
    }
}
