<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Events\NewUser;
use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\UserController
 */
class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertOk();
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user));

        $response->assertOk();
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('user.create'));

        $response->assertOk();
        $response->assertViewIs('admin.users.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\UserController::class,
            'store',
            \App\Http\Requests\Admin\UserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $is_active = $this->faker->boolean;
        $is_admin = $this->faker->boolean;
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;

        Event::fake();
        Mail::fake();

        $response = $this->post(route('user.store'), [
            'is_active' => $is_active,
            'is_admin' => $is_admin,
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $users = User::query()
            ->where('is_active', $is_active)
            ->where('is_admin', $is_admin)
            ->where('name', $name)
            ->where('email', $email)
            ->where('password', $password)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('user.name', $user->name);

        Event::assertDispatched(NewUser::class, function ($event) use ($user) {
            return $event->user->is($user);
        });
        Mail::assertSent(NewUserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->is($user);
        });
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.edit', $user));

        $response->assertOk();
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('user');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\UserController::class,
            'update',
            \App\Http\Requests\Admin\UserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $is_active = $this->faker->boolean;
        $is_admin = $this->faker->boolean;
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;

        $response = $this->put(route('user.update', $user), [
            'is_active' => $is_active,
            'is_admin' => $is_admin,
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $users = User::query()
            ->where('is_active', $is_active)
            ->where('is_admin', $is_admin)
            ->where('name', $name)
            ->where('email', $email)
            ->where('password', $password)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertRedirect(route('admin.users.index'));
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));

        $this->assertModelMissing($user);
    }
}
