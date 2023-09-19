<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewReview;
use App\Mail\NewReviewMail;
use App\Models\Review;
use App\Models\Reviewer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReviewController
 */
class ReviewControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $reviews = Review::factory()->count(3)->create();

        $response = $this->get(route('review.index'));

        $response->assertOk();
        $response->assertViewIs('admin.reviews.index');
        $response->assertViewHas('reviews');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $review = Review::factory()->create();

        $response = $this->get(route('review.show', $review));

        $response->assertOk();
        $response->assertViewIs('admin.reviews.show');
        $response->assertViewHas('review');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('review.create'));

        $response->assertOk();
        $response->assertViewIs('admin.reviews.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReviewController::class,
            'store',
            \App\Http\Requests\Admin\ReviewStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $reviewer = Reviewer::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);

        Event::fake();
        Mail::fake();

        $response = $this->post(route('review.store'), [
            'reviewer_id' => $reviewer->id,
            'rating' => $rating,
        ]);

        $reviews = Review::query()
            ->where('reviewer_id', $reviewer->id)
            ->where('rating', $rating)
            ->get();
        $this->assertCount(1, $reviews);
        $review = $reviews->first();

        $response->assertRedirect(route('admin.reviews.index'));
        $response->assertSessionHas('review.name', $review->name);

        Event::assertDispatched(NewReview::class, function ($event) use ($review) {
            return $event->review->is($review);
        });
        Mail::assertSent(NewReviewMail::class, function ($mail) use ($review) {
            return $mail->hasTo($review->email) && $mail->review->is($review);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $review = Review::factory()->create();

        $response = $this->get(route('review.edit', $review));

        $response->assertOk();
        $response->assertViewIs('admin.reviews.edit');
        $response->assertViewHas('review');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ReviewController::class,
            'update',
            \App\Http\Requests\Admin\ReviewUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $review = Review::factory()->create();
        $reviewer = Reviewer::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('review.update', $review), [
            'reviewer_id' => $reviewer->id,
            'rating' => $rating,
        ]);

        $reviews = Review::query()
            ->where('reviewer_id', $reviewer->id)
            ->where('rating', $rating)
            ->get();
        $this->assertCount(1, $reviews);
        $review = $reviews->first();

        $response->assertRedirect(route('admin.reviews.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('review.destroy', $review));

        $response->assertRedirect(route('admin.reviews.index'));

        $this->assertModelMissing($review);
    }
}
