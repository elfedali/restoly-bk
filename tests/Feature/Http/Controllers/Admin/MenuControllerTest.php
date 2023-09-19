<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\MenuController
 */
class MenuControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $menus = Menu::factory()->count(3)->create();

        $response = $this->get(route('menu.index'));

        $response->assertOk();
        $response->assertViewIs('admin.menus.index');
        $response->assertViewHas('menus');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->get(route('menu.show', $menu));

        $response->assertOk();
        $response->assertViewIs('admin.menus.show');
        $response->assertViewHas('menu');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('menu.create'));

        $response->assertOk();
        $response->assertViewIs('admin.menus.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\MenuController::class,
            'store',
            \App\Http\Requests\Admin\MenuStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $restaurant = Restaurant::factory()->create();
        $name = $this->faker->name;
        $description = $this->faker->text;

        $response = $this->post(route('menu.store'), [
            'restaurant_id' => $restaurant->id,
            'name' => $name,
            'description' => $description,
        ]);

        $menus = Menu::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', $name)
            ->where('description', $description)
            ->get();
        $this->assertCount(1, $menus);
        $menu = $menus->first();

        $response->assertRedirect(route('admin.menus.index'));
        $response->assertSessionHas('menu.name', $menu->name);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->get(route('menu.edit', $menu));

        $response->assertOk();
        $response->assertViewIs('admin.menus.edit');
        $response->assertViewHas('menu');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\MenuController::class,
            'update',
            \App\Http\Requests\Admin\MenuUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $menu = Menu::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $name = $this->faker->name;
        $description = $this->faker->text;

        $response = $this->put(route('menu.update', $menu), [
            'restaurant_id' => $restaurant->id,
            'name' => $name,
            'description' => $description,
        ]);

        $menus = Menu::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', $name)
            ->where('description', $description)
            ->get();
        $this->assertCount(1, $menus);
        $menu = $menus->first();

        $response->assertRedirect(route('admin.menus.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->delete(route('menu.destroy', $menu));

        $response->assertRedirect(route('admin.menus.index'));

        $this->assertModelMissing($menu);
    }
}
