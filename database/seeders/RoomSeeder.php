<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $property1 = Property::where('name', 'Sunset Hotel')->first();
        $property2 = Property::where('name', 'Mountain View Resort')->first();

        Room::create([
            'property_id' => $property1->id,
            'name' => 'Deluxe Suite',
            'capacity' => 2,
            'price_per_night' => 150.00,
        ]);

        Room::create([
            'property_id' => $property1->id,
            'name' => 'Standard Room',
            'capacity' => 2,
            'price_per_night' => 100.00,
        ]);

        Room::create([
            'property_id' => $property2->id,
            'name' => 'Luxury Villa',
            'capacity' => 4,
            'price_per_night' => 300.00,
        ]);
    }
}
