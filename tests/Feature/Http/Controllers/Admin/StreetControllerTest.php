<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Street;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\StreetController
 */
class StreetControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $streets = Street::factory()->count(3)->create();

        $response = $this->get(route('street.index'));

        $response->assertOk();
        $response->assertViewIs('admin.streets.index');
        $response->assertViewHas('streets');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $street = Street::factory()->create();

        $response = $this->get(route('street.show', $street));

        $response->assertOk();
        $response->assertViewIs('admin.streets.show');
        $response->assertViewHas('street');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('street.create'));

        $response->assertOk();
        $response->assertViewIs('admin.streets.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\StreetController::class,
            'store',
            \App\Http\Requests\Admin\StreetStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $city = City::factory()->create();
        $name = $this->faker->name;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('street.store'), [
            'city_id' => $city->id,
            'name' => $name,
            'is_active' => $is_active,
        ]);

        $streets = Street::query()
            ->where('city_id', $city->id)
            ->where('name', $name)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $streets);
        $street = $streets->first();

        $response->assertRedirect(route('admin.streets.index'));
        $response->assertSessionHas('street.name', $street->name);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $street = Street::factory()->create();

        $response = $this->get(route('street.edit', $street));

        $response->assertOk();
        $response->assertViewIs('admin.streets.edit');
        $response->assertViewHas('street');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\StreetController::class,
            'update',
            \App\Http\Requests\Admin\StreetUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $street = Street::factory()->create();
        $city = City::factory()->create();
        $name = $this->faker->name;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('street.update', $street), [
            'city_id' => $city->id,
            'name' => $name,
            'is_active' => $is_active,
        ]);

        $streets = Street::query()
            ->where('city_id', $city->id)
            ->where('name', $name)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $streets);
        $street = $streets->first();

        $response->assertRedirect(route('admin.streets.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $street = Street::factory()->create();

        $response = $this->delete(route('street.destroy', $street));

        $response->assertRedirect(route('admin.streets.index'));

        $this->assertModelMissing($street);
    }
}
