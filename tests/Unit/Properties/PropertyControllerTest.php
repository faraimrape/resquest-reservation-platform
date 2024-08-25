<?php

namespace Tests\Unit\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_property_list_without_search_filter()
    {
        $this->actingAs(User::factory()->create());

        Property::factory()->count(5)->create();

        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Properties/Index'));
    }

    /** @test */
    public function it_creates_a_property()
    {
        $this->actingAs(User::factory()->create());

        $data = [
            'name' => 'Oceanview Resort',
            'location' => '123 Beach Road',
            'is_published' => true,
            'description' => 'Oceanview Resort',
            'image_url' => 'testurl',
        ];

        $response = $this->post(route('properties.store'), $data);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', ['name' => 'Oceanview Resort']);
    }

    /** @test */
    public function it_updates_a_property()
    {
        $this->actingAs(User::factory()->create());

        $property = Property::factory()->create();

        $data = [
            'name' => 'Updated Property Name',
            'location' => $property->location,
            'is_published' => $property->is_published,
        ];

        $response = $this->put(route('properties.update', $property), $data);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => 'Updated Property Name',
        ]);
    }

}
