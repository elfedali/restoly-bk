<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PlanController
 */
class PlanControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $plans = Plan::factory()->count(3)->create();

        $response = $this->get(route('plan.index'));

        $response->assertOk();
        $response->assertViewIs('admin.plans.index');
        $response->assertViewHas('plans');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $plan = Plan::factory()->create();

        $response = $this->get(route('plan.show', $plan));

        $response->assertOk();
        $response->assertViewIs('admin.plans.show');
        $response->assertViewHas('plan');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('plan.create'));

        $response->assertOk();
        $response->assertViewIs('admin.plans.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PlanController::class,
            'store',
            \App\Http\Requests\Admin\PlanStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $is_active = $this->faker->boolean;

        $response = $this->post(route('plan.store'), [
            'name' => $name,
            'slug' => $slug,
            'price' => $price,
            'is_active' => $is_active,
        ]);

        $plans = Plan::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('price', $price)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $plans);
        $plan = $plans->first();

        $response->assertRedirect(route('admin.plans.index'));
        $response->assertSessionHas('plan.name', $plan->name);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $plan = Plan::factory()->create();

        $response = $this->get(route('plan.edit', $plan));

        $response->assertOk();
        $response->assertViewIs('admin.plans.edit');
        $response->assertViewHas('plan');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PlanController::class,
            'update',
            \App\Http\Requests\Admin\PlanUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $plan = Plan::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $is_active = $this->faker->boolean;

        $response = $this->put(route('plan.update', $plan), [
            'name' => $name,
            'slug' => $slug,
            'price' => $price,
            'is_active' => $is_active,
        ]);

        $plans = Plan::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('price', $price)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $plans);
        $plan = $plans->first();

        $response->assertRedirect(route('admin.plans.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $plan = Plan::factory()->create();

        $response = $this->delete(route('plan.destroy', $plan));

        $response->assertRedirect(route('admin.plans.index'));

        $this->assertModelMissing($plan);
    }
}
