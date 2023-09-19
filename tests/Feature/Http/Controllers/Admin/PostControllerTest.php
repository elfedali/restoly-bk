<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewPost;
use App\Mail\NewPostMail;
use App\Models\Author;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PostController
 */
class PostControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get(route('post.index'));

        $response->assertOk();
        $response->assertViewIs('admin.posts.index');
        $response->assertViewHas('posts');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.show', $post));

        $response->assertOk();
        $response->assertViewIs('admin.posts.show');
        $response->assertViewHas('post');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('post.create'));

        $response->assertOk();
        $response->assertViewIs('admin.posts.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PostController::class,
            'store',
            \App\Http\Requests\Admin\PostStoreRequest::class
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

        Event::fake();
        Mail::fake();

        $response = $this->post(route('post.store'), [
            'title' => $title,
            'slug' => $slug,
            'author_id' => $author->id,
            'is_active' => $is_active,
        ]);

        $posts = Post::query()
            ->where('title', $title)
            ->where('slug', $slug)
            ->where('author_id', $author->id)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $posts);
        $post = $posts->first();

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('post.title', $post->title);

        Event::assertDispatched(NewPost::class, function ($event) use ($post) {
            return $event->post->is($post);
        });
        Mail::assertSent(NewPostMail::class, function ($mail) use ($post) {
            return $mail->hasTo($post->author) && $mail->post->is($post);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.edit', $post));

        $response->assertOk();
        $response->assertViewIs('admin.posts.edit');
        $response->assertViewHas('post');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PostController::class,
            'update',
            \App\Http\Requests\Admin\PostUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $post = Post::factory()->create();
        $title = $this->faker->sentence(4);
        $slug = $this->faker->slug;
        $author = Author::factory()->create();
        $is_active = $this->faker->boolean;

        $response = $this->put(route('post.update', $post), [
            'title' => $title,
            'slug' => $slug,
            'author_id' => $author->id,
            'is_active' => $is_active,
        ]);

        $posts = Post::query()
            ->where('title', $title)
            ->where('slug', $slug)
            ->where('author_id', $author->id)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $posts);
        $post = $posts->first();

        $response->assertRedirect(route('admin.posts.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('post.destroy', $post));

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertModelMissing($post);
    }
}
