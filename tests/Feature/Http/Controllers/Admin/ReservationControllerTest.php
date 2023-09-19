<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewReservation;
use App\Mail\NewReservationMail;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReservationController
 */
class ReservationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $reservations = Reservation::factory()->count(3)->create();

        $response = $this->get(route('reservation.index'));

        $response->assertOk();
        $response->assertViewIs('admin.reservations.index');
        $response->assertViewHas('reservations');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->get(route('reservation.show', $reservation));

        $response->assertOk();
        $response->assertViewIs('admin.reservations.show');
        $response->assertViewHas('reservation');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('reservation.create'));

        $response->assertOk();
        $response->assertViewIs('admin.reservations.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReservationController::class,
            'store',
            \App\Http\Requests\Admin\ReservationStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $table = Table::factory()->create();
        $client = Client::factory()->create();
        $arrival_date = $this->faker->dateTime();
        $status = $this->faker->randomElement(/** enum_attributes **/);

        Event::fake();
        Mail::fake();

        $response = $this->post(route('reservation.store'), [
            'table_id' => $table->id,
            'client_id' => $client->id,
            'arrival_date' => $arrival_date,
            'status' => $status,
        ]);

        $reservations = Reservation::query()
            ->where('table_id', $table->id)
            ->where('client_id', $client->id)
            ->where('arrival_date', $arrival_date)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $reservations);
        $reservation = $reservations->first();

        $response->assertRedirect(route('admin.reservations.index'));
        $response->assertSessionHas('reservation.name', $reservation->name);

        Event::assertDispatched(NewReservation::class, function ($event) use ($reservation) {
            return $event->reservation->is($reservation);
        });
        Mail::assertSent(NewReservationMail::class, function ($mail) use ($reservation) {
            return $mail->hasTo($reservation->email) && $mail->reservation->is($reservation);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->get(route('reservation.edit', $reservation));

        $response->assertOk();
        $response->assertViewIs('admin.reservations.edit');
        $response->assertViewHas('reservation');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReservationController::class,
            'update',
            \App\Http\Requests\Admin\ReservationUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $reservation = Reservation::factory()->create();
        $table = Table::factory()->create();
        $client = Client::factory()->create();
        $arrival_date = $this->faker->dateTime();
        $status = $this->faker->randomElement(/** enum_attributes **/);

        $response = $this->put(route('reservation.update', $reservation), [
            'table_id' => $table->id,
            'client_id' => $client->id,
            'arrival_date' => $arrival_date,
            'status' => $status,
        ]);

        $reservations = Reservation::query()
            ->where('table_id', $table->id)
            ->where('client_id', $client->id)
            ->where('arrival_date', $arrival_date)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $reservations);
        $reservation = $reservations->first();

        $response->assertRedirect(route('admin.reservations.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->delete(route('reservation.destroy', $reservation));

        $response->assertRedirect(route('admin.reservations.index'));

        $this->assertModelMissing($reservation);
    }
}
