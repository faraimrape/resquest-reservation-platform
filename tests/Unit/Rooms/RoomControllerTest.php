<?php

namespace Tests\Unit\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_room_list_without_search_filter()
    {
        $this->actingAs(User::factory()->create());

        Room::factory()->count(5)->create();

        $response = $this->get(route('rooms.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Rooms/Index'));
    }

    /** @test */
    public function it_creates_a_room()
    {
        $this->actingAs(User::factory()->create());

        $property = Property::factory()->create();

        $data = [
            'name' => 'Deluxe Room',
            'property_id' => $property->id,
            'capacity' => 2,
            'price_per_night' => 150,
        ];

        $response = $this->post(route('rooms.store'), $data);

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseHas('rooms', [
            'name' => 'Deluxe Room',
            'property_id' => $property->id,
        ]);
    }

    /** @test */
    public function it_updates_a_room()
    {
        $this->actingAs(User::factory()->create());

        $room = Room::factory()->create();

        $data = [
            'name' => 'Updated Room Name',
            'property_id' => $room->property_id,
            'capacity' => $room->capacity,
            'price_per_night' => $room->price_per_night,
        ];

        $response = $this->put(route('rooms.update', $room), $data);

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'name' => 'Updated Room Name',
        ]);
    }

    /** @test */
    public function it_deletes_a_room()
    {
        $this->actingAs(User::factory()->create());

        $room = Room::factory()->create();

        $response = $this->delete(route('rooms.destroy', $room));

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }
}
