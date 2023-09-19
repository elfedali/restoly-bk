<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PermissionController
 */
class PermissionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $permissions = Permission::factory()->count(3)->create();

        $response = $this->get(route('permission.index'));

        $response->assertOk();
        $response->assertViewIs('admin.permissions.index');
        $response->assertViewHas('permissions');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('permission.show', $permission));

        $response->assertOk();
        $response->assertViewIs('admin.permissions.show');
        $response->assertViewHas('permission');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('permission.create'));

        $response->assertOk();
        $response->assertViewIs('admin.permissions.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PermissionController::class,
            'store',
            \App\Http\Requests\Admin\PermissionStoreRequest::class
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

        $response = $this->post(route('permission.store'), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $permissions = Permission::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $permissions);
        $permission = $permissions->first();

        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('permission.name', $permission->name);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('permission.edit', $permission));

        $response->assertOk();
        $response->assertViewIs('admin.permissions.edit');
        $response->assertViewHas('permission');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PermissionController::class,
            'update',
            \App\Http\Requests\Admin\PermissionUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $permission = Permission::factory()->create();
        $name = $this->faker->name;
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('permission.update', $permission), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $permissions = Permission::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $permissions);
        $permission = $permissions->first();

        $response->assertRedirect(route('admin.permissions.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->delete(route('permission.destroy', $permission));

        $response->assertRedirect(route('admin.permissions.index'));

        $this->assertModelMissing($permission);
    }
}
