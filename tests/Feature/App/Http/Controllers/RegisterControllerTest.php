<?php

namespace Tests\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Listeners\SendEmailUserListener;
use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle()
    {
        Notification::fake();
        Event::fake();

        $request = [
            'name'                  => 'Test',
            'email'                 => 'testing@cutcode.ru',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);


        $response = $this->post(
            action([RegisterController::class, 'handle']),
            $request,
        );

        $response->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

        $user = User::query()
            ->where('email', $request['email'])
            ->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailUserListener::class);

        $event    = new Registered($user);
        $listener = new SendEmailUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);
        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    public function testPage()
    {
        $this->get(action([RegisterController::class, 'page']))
            ->assertOk()
            ->assertSee('Зарегистрироваться')
            ->assertViewIs('auth.register');
    }
}
