<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewPayment;
use App\Mail\NewPaymentMail;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PaymentController
 */
class PaymentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $payments = Payment::factory()->count(3)->create();

        $response = $this->get(route('payment.index'));

        $response->assertOk();
        $response->assertViewIs('admin.payments.index');
        $response->assertViewHas('payments');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payment.show', $payment));

        $response->assertOk();
        $response->assertViewIs('admin.payments.show');
        $response->assertViewHas('payment');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('payment.create'));

        $response->assertOk();
        $response->assertViewIs('admin.payments.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PaymentController::class,
            'store',
            \App\Http\Requests\Admin\PaymentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);

        Event::fake();
        Mail::fake();

        $response = $this->post(route('payment.store'), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $amount,
        ]);

        $payments = Payment::query()
            ->where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertRedirect(route('admin.payments.index'));
        $response->assertSessionHas('payment.name', $payment->name);

        Event::assertDispatched(NewPayment::class, function ($event) use ($payment) {
            return $event->payment->is($payment);
        });
        Mail::assertSent(NewPaymentMail::class, function ($mail) use ($payment) {
            return $mail->hasTo($payment->email) && $mail->payment->is($payment);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payment.edit', $payment));

        $response->assertOk();
        $response->assertViewIs('admin.payments.edit');
        $response->assertViewHas('payment');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PaymentController::class,
            'update',
            \App\Http\Requests\Admin\PaymentUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $payment = Payment::factory()->create();
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);

        $response = $this->put(route('payment.update', $payment), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $amount,
        ]);

        $payments = Payment::query()
            ->where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertRedirect(route('admin.payments.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payment.destroy', $payment));

        $response->assertRedirect(route('admin.payments.index'));

        $this->assertModelMissing($payment);
    }
}
