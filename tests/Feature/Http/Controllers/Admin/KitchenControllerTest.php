<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewKitchen;
use App\Models\Kitchen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\KitchenController
 */
class KitchenControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $kitchens = Kitchen::factory()->count(3)->create();

        $response = $this->get(route('kitchen.index'));

        $response->assertOk();
        $response->assertViewIs('admin.kitchens.index');
        $response->assertViewHas('kitchens');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $kitchen = Kitchen::factory()->create();

        $response = $this->get(route('kitchen.show', $kitchen));

        $response->assertOk();
        $response->assertViewIs('admin.kitchens.show');
        $response->assertViewHas('kitchen');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('kitchen.create'));

        $response->assertOk();
        $response->assertViewIs('admin.kitchens.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\KitchenController::class,
            'store',
            \App\Http\Requests\Admin\KitchenStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        Event::fake();

        $response = $this->post(route('kitchen.store'), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $kitchens = Kitchen::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $kitchens);
        $kitchen = $kitchens->first();

        $response->assertRedirect(route('admin.kitchens.index'));

        Event::assertDispatched(NewKitchen::class, function ($event) use ($kitchen) {
            return $event->kitchen->is($kitchen);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $kitchen = Kitchen::factory()->create();

        $response = $this->get(route('kitchen.edit', $kitchen));

        $response->assertOk();
        $response->assertViewIs('admin.kitchens.edit');
        $response->assertViewHas('kitchen');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\KitchenController::class,
            'update',
            \App\Http\Requests\Admin\KitchenUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $kitchen = Kitchen::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('kitchen.update', $kitchen), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $kitchens = Kitchen::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $kitchens);
        $kitchen = $kitchens->first();

        $response->assertRedirect(route('admin.kitchens.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $kitchen = Kitchen::factory()->create();

        $response = $this->delete(route('kitchen.destroy', $kitchen));

        $response->assertRedirect(route('admin.kitchens.index'));

        $this->assertModelMissing($kitchen);
    }
}
