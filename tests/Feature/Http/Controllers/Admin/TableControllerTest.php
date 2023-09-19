<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Salle;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\TableController
 */
class TableControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $tables = Table::factory()->count(3)->create();

        $response = $this->get(route('table.index'));

        $response->assertOk();
        $response->assertViewIs('admin.tables.index');
        $response->assertViewHas('tables');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $table = Table::factory()->create();

        $response = $this->get(route('table.show', $table));

        $response->assertOk();
        $response->assertViewIs('admin.tables.show');
        $response->assertViewHas('table');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('table.create'));

        $response->assertOk();
        $response->assertViewIs('admin.tables.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\TableController::class,
            'store',
            \App\Http\Requests\Admin\TableStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $salle = Salle::factory()->create();
        $name = $this->faker->name;
        $capacity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('table.store'), [
            'salle_id' => $salle->id,
            'name' => $name,
            'capacity' => $capacity,
        ]);

        $tables = Table::query()
            ->where('salle_id', $salle->id)
            ->where('name', $name)
            ->where('capacity', $capacity)
            ->get();
        $this->assertCount(1, $tables);
        $table = $tables->first();

        $response->assertRedirect(route('admin.tables.index'));
        $response->assertSessionHas('table.name', $table->name);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $table = Table::factory()->create();

        $response = $this->get(route('table.edit', $table));

        $response->assertOk();
        $response->assertViewIs('admin.tables.edit');
        $response->assertViewHas('table');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\TableController::class,
            'update',
            \App\Http\Requests\Admin\TableUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $table = Table::factory()->create();
        $salle = Salle::factory()->create();
        $name = $this->faker->name;
        $capacity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('table.update', $table), [
            'salle_id' => $salle->id,
            'name' => $name,
            'capacity' => $capacity,
        ]);

        $tables = Table::query()
            ->where('salle_id', $salle->id)
            ->where('name', $name)
            ->where('capacity', $capacity)
            ->get();
        $this->assertCount(1, $tables);
        $table = $tables->first();

        $response->assertRedirect(route('admin.tables.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $table = Table::factory()->create();

        $response = $this->delete(route('table.destroy', $table));

        $response->assertRedirect(route('admin.tables.index'));

        $this->assertModelMissing($table);
    }
}
