<?php

namespace Tests\Unit\Home;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_home_page_with_properties()
    {
        // Arrange
        Property::factory()->count(3)->create(['is_published' => true]);

        // Act
        $response = $this->get('/'); // Use the URL directly

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(function ($page) {
            $page->component('Home')
                ->has('properties.data', 3);
        });
    }

    /** @test */
    public function it_searches_properties_on_home_page()
    {
        // Arrange
        $property = Property::factory()->create(['name' => 'Beachfront Villa', 'is_published' => true]);

        // Act
        $response = $this->get('/?search=Beachfront'); // Use the URL directly with query parameters

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(function ($page) use ($property) {
            $page->component('Home')
                ->has('properties.data', 1)
                ->where('properties.data.0.id', $property->id);
        });
    }
}
