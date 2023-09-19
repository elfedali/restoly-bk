<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewSubscription;
use App\Mail\NewSubscriptionMail;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\SubscriptionController
 */
class SubscriptionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $subscriptions = Subscription::factory()->count(3)->create();

        $response = $this->get(route('subscription.index'));

        $response->assertOk();
        $response->assertViewIs('admin.subscriptions.index');
        $response->assertViewHas('subscriptions');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $subscription = Subscription::factory()->create();

        $response = $this->get(route('subscription.show', $subscription));

        $response->assertOk();
        $response->assertViewIs('admin.subscriptions.show');
        $response->assertViewHas('subscription');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('subscription.create'));

        $response->assertOk();
        $response->assertViewIs('admin.subscriptions.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\SubscriptionController::class,
            'store',
            \App\Http\Requests\Admin\SubscriptionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $start_date = $this->faker->dateTime();
        $end_date = $this->faker->dateTime();

        Event::fake();
        Mail::fake();

        $response = $this->post(route('subscription.store'), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $subscriptions = Subscription::query()
            ->where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->get();
        $this->assertCount(1, $subscriptions);
        $subscription = $subscriptions->first();

        $response->assertRedirect(route('admin.subscriptions.index'));
        $response->assertSessionHas('subscription.name', $subscription->name);

        Event::assertDispatched(NewSubscription::class, function ($event) use ($subscription) {
            return $event->subscription->is($subscription);
        });
        Mail::assertSent(NewSubscriptionMail::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->email) && $mail->subscription->is($subscription);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $subscription = Subscription::factory()->create();

        $response = $this->get(route('subscription.edit', $subscription));

        $response->assertOk();
        $response->assertViewIs('admin.subscriptions.edit');
        $response->assertViewHas('subscription');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\SubscriptionController::class,
            'update',
            \App\Http\Requests\Admin\SubscriptionUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $subscription = Subscription::factory()->create();
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $start_date = $this->faker->dateTime();
        $end_date = $this->faker->dateTime();

        $response = $this->put(route('subscription.update', $subscription), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $subscriptions = Subscription::query()
            ->where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->get();
        $this->assertCount(1, $subscriptions);
        $subscription = $subscriptions->first();

        $response->assertRedirect(route('admin.subscriptions.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $subscription = Subscription::factory()->create();

        $response = $this->delete(route('subscription.destroy', $subscription));

        $response->assertRedirect(route('admin.subscriptions.index'));

        $this->assertModelMissing($subscription);
    }
}
