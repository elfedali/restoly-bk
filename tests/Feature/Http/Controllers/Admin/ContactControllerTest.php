<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewContact;
use App\Mail\NewContactMail;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ContactController
 */
class ContactControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $contacts = Contact::factory()->count(3)->create();

        $response = $this->get(route('contact.index'));

        $response->assertOk();
        $response->assertViewIs('admin.contacts.index');
        $response->assertViewHas('contacts');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contact.show', $contact));

        $response->assertOk();
        $response->assertViewIs('admin.contacts.show');
        $response->assertViewHas('contact');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('contact.create'));

        $response->assertOk();
        $response->assertViewIs('admin.contacts.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ContactController::class,
            'store',
            \App\Http\Requests\Admin\ContactStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $message = $this->faker->text;

        Event::fake();
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name' => $name,
            'message' => $message,
        ]);

        $contacts = Contact::query()
            ->where('name', $name)
            ->where('message', $message)
            ->get();
        $this->assertCount(1, $contacts);
        $contact = $contacts->first();

        $response->assertRedirect(route('admin.contacts.index'));
        $response->assertSessionHas('contact.name', $contact->name);

        Event::assertDispatched(NewContact::class, function ($event) use ($contact) {
            return $event->contact->is($contact);
        });
        Mail::assertSent(NewContactMail::class, function ($mail) use ($contact) {
            return $mail->hasTo($contact->email) && $mail->contact->is($contact);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contact.edit', $contact));

        $response->assertOk();
        $response->assertViewIs('admin.contacts.edit');
        $response->assertViewHas('contact');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ContactController::class,
            'update',
            \App\Http\Requests\Admin\ContactUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $contact = Contact::factory()->create();
        $name = $this->faker->name;
        $message = $this->faker->text;

        $response = $this->put(route('contact.update', $contact), [
            'name' => $name,
            'message' => $message,
        ]);

        $contacts = Contact::query()
            ->where('name', $name)
            ->where('message', $message)
            ->get();
        $this->assertCount(1, $contacts);
        $contact = $contacts->first();

        $response->assertRedirect(route('admin.contacts.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contact.destroy', $contact));

        $response->assertRedirect(route('admin.contacts.index'));

        $this->assertModelMissing($contact);
    }
}
