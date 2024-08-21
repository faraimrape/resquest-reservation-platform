<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        Property::create([
            'name' => 'Sunset Hotel',
            'description' => 'A beautiful hotel near the beach.',
            'location' => 'California, USA',
            'is_published' => true,
        ]);

        Property::create([
            'name' => 'Mountain View Resort',
            'description' => 'A luxury resort in the mountains.',
            'location' => 'Colorado, USA',
            'is_published' => true,
        ]);
    }
}
