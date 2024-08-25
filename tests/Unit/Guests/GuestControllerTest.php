<?php

namespace Tests\Unit\Controllers;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_guest_list_without_search_filter()
    {
        // Authenticate user
        $this->actingAs(User::factory()->create());

        // Arrange
        Guest::factory()->count(5)->create();

        // Act
        $response = $this->get(route('guests.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Guests/Index'));
    }


    /** @test */
    public function it_updates_a_guest()
    {
        $this->actingAs(User::factory()->create());

        $guest = Guest::factory()->create([
            'first_name' => 'Old Name',
            'email' => 'oldemail@example.com',
        ]);

        $data = [
            'first_name' => 'New Name',
            'last_name' => $guest->last_name,
            'email' => 'newemail@example.com',
            'phone' => $guest->phone,
        ];

        $response = $this->put(route('guests.update', $guest), $data);

        $response->assertRedirect(route('guests.index'));
        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'first_name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);
    }

    /** @test */
    public function it_deletes_a_guest()
    {
        $this->actingAs(User::factory()->create());

        $guest = Guest::factory()->create();

        $response = $this->delete(route('guests.destroy', $guest));

        $response->assertRedirect(route('guests.index'));
        $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
    }
}
