<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewRestaurant;
use App\Jobs\SyncMedia;
use App\Mail\NewRestaurantMail;
use App\Models\City;
use App\Models\Owner;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\RestaurantController
 */
class RestaurantControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $restaurants = Restaurant::factory()->count(3)->create();

        $response = $this->get(route('restaurant.index'));

        $response->assertOk();
        $response->assertViewIs('admin.restaurants.index');
        $response->assertViewHas('restaurants');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurant.show', $restaurant));

        $response->assertOk();
        $response->assertViewIs('admin.restaurants.show');
        $response->assertViewHas('restaurant');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('restaurant.create'));

        $response->assertOk();
        $response->assertViewIs('admin.restaurants.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\RestaurantController::class,
            'store',
            \App\Http\Requests\Admin\RestaurantStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $owner = Owner::factory()->create();
        $city = City::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;
        $is_verified = $this->faker->boolean;
        $is_featured = $this->faker->boolean;

        Event::fake();
        Mail::fake();
        Queue::fake();

        $response = $this->post(route('restaurant.store'), [
            'owner_id' => $owner->id,
            'city_id' => $city->id,
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
            'is_verified' => $is_verified,
            'is_featured' => $is_featured,
        ]);

        $restaurants = Restaurant::query()
            ->where('owner_id', $owner->id)
            ->where('city_id', $city->id)
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->where('is_verified', $is_verified)
            ->where('is_featured', $is_featured)
            ->get();
        $this->assertCount(1, $restaurants);
        $restaurant = $restaurants->first();

        $response->assertRedirect(route('admin.restaurants.index'));
        $response->assertSessionHas('restaurant.name', $restaurant->name);

        Event::assertDispatched(NewRestaurant::class, function ($event) use ($restaurant) {
            return $event->restaurant->is($restaurant);
        });
        Mail::assertSent(NewRestaurantMail::class, function ($mail) use ($restaurant) {
            return $mail->hasTo($restaurant->email) && $mail->restaurant->is($restaurant);
        });
        Queue::assertPushed(SyncMedia::class, function ($job) use ($restaurant) {
            return $job->restaurant->is($restaurant);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurant.edit', $restaurant));

        $response->assertOk();
        $response->assertViewIs('admin.restaurants.edit');
        $response->assertViewHas('restaurant');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\RestaurantController::class,
            'update',
            \App\Http\Requests\Admin\RestaurantUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $restaurant = Restaurant::factory()->create();
        $owner = Owner::factory()->create();
        $city = City::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;
        $is_verified = $this->faker->boolean;
        $is_featured = $this->faker->boolean;

        $response = $this->put(route('restaurant.update', $restaurant), [
            'owner_id' => $owner->id,
            'city_id' => $city->id,
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
            'is_verified' => $is_verified,
            'is_featured' => $is_featured,
        ]);

        $restaurants = Restaurant::query()
            ->where('owner_id', $owner->id)
            ->where('city_id', $city->id)
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->where('is_verified', $is_verified)
            ->where('is_featured', $is_featured)
            ->get();
        $this->assertCount(1, $restaurants);
        $restaurant = $restaurants->first();

        $response->assertRedirect(route('admin.restaurants.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('restaurant.destroy', $restaurant));

        $response->assertRedirect(route('admin.restaurants.index'));

        $this->assertModelMissing($restaurant);
    }
}
