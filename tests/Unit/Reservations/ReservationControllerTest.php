<?php

namespace Tests\Unit\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_reservation_list_without_search_filter()
    {
        $this->actingAs(User::factory()->create());

        Reservation::factory()->count(5)->create();

        $response = $this->get(route('reservations.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Reservations/Index'));
    }



    /** @test */
    public function it_deletes_a_reservation()
    {
        $this->actingAs(User::factory()->create());

        $reservation = Reservation::factory()->create();

        $response = $this->delete(route('reservations.destroy', $reservation));

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }
}
