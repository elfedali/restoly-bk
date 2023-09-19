<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Author;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PageController
 */
class PageControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $pages = Page::factory()->count(3)->create();

        $response = $this->get(route('page.index'));

        $response->assertOk();
        $response->assertViewIs('admin.pages.index');
        $response->assertViewHas('pages');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $page = Page::factory()->create();

        $response = $this->get(route('page.show', $page));

        $response->assertOk();
        $response->assertViewIs('admin.pages.show');
        $response->assertViewHas('page');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('page.create'));

        $response->assertOk();
        $response->assertViewIs('admin.pages.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PageController::class,
            'store',
            \App\Http\Requests\Admin\PageStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $title = $this->faker->sentence(4);
        $slug = $this->faker->slug;
        $author = Author::factory()->create();
        $is_active = $this->faker->boolean;

        $response = $this->post(route('page.store'), [
            'title' => $title,
            'slug' => $slug,
            'author_id' => $author->id,
            'is_active' => $is_active,
        ]);

        $pages = Page::query()
            ->where('title', $title)
            ->where('slug', $slug)
            ->where('author_id', $author->id)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $pages);
        $page = $pages->first();

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('page.title', $page->title);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $page = Page::factory()->create();

        $response = $this->get(route('page.edit', $page));

        $response->assertOk();
        $response->assertViewIs('admin.pages.edit');
        $response->assertViewHas('page');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PageController::class,
            'update',
            \App\Http\Requests\Admin\PageUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $page = Page::factory()->create();
        $title = $this->faker->sentence(4);
        $slug = $this->faker->slug;
        $author = Author::factory()->create();
        $is_active = $this->faker->boolean;

        $response = $this->put(route('page.update', $page), [
            'title' => $title,
            'slug' => $slug,
            'author_id' => $author->id,
            'is_active' => $is_active,
        ]);

        $pages = Page::query()
            ->where('title', $title)
            ->where('slug', $slug)
            ->where('author_id', $author->id)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $pages);
        $page = $pages->first();

        $response->assertRedirect(route('admin.pages.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $page = Page::factory()->create();

        $response = $this->delete(route('page.destroy', $page));

        $response->assertRedirect(route('admin.pages.index'));

        $this->assertModelMissing($page);
    }
}
