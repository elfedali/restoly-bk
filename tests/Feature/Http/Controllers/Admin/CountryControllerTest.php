<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\CountryController
 */
class CountryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $countries = Country::factory()->count(3)->create();

        $response = $this->get(route('country.index'));

        $response->assertOk();
        $response->assertViewIs('admin.countries.index');
        $response->assertViewHas('countries');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $country = Country::factory()->create();

        $response = $this->get(route('country.show', $country));

        $response->assertOk();
        $response->assertViewIs('admin.countries.show');
        $response->assertViewHas('country');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('country.create'));

        $response->assertOk();
        $response->assertViewIs('admin.countries.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\CountryController::class,
            'store',
            \App\Http\Requests\Admin\CountryStoreRequest::class
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
        $lang = $this->faker->word;

        $response = $this->post(route('country.store'), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
            'lang' => $lang,
        ]);

        $countries = Country::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->where('lang', $lang)
            ->get();
        $this->assertCount(1, $countries);
        $country = $countries->first();

        $response->assertRedirect(route('admin.countries.index'));
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $country = Country::factory()->create();

        $response = $this->get(route('country.edit', $country));

        $response->assertOk();
        $response->assertViewIs('admin.countries.edit');
        $response->assertViewHas('country');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\CountryController::class,
            'update',
            \App\Http\Requests\Admin\CountryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $country = Country::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;
        $lang = $this->faker->word;

        $response = $this->put(route('country.update', $country), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
            'lang' => $lang,
        ]);

        $countries = Country::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->where('lang', $lang)
            ->get();
        $this->assertCount(1, $countries);
        $country = $countries->first();

        $response->assertRedirect(route('admin.countries.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $country = Country::factory()->create();

        $response = $this->delete(route('country.destroy', $country));

        $response->assertRedirect(route('admin.countries.index'));

        $this->assertModelMissing($country);
    }
}
