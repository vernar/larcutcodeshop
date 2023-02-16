<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginPostRequest;
use App\Listeners\SendEmailUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {
        $this->get(action([AuthController::class, 'login']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function testLoginPost()
    {
        $password = '12345678';
        $user     = User::factory()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $request = LoginPostRequest::factory()->create([
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([AuthController::class, 'loginPost']), $request);
        $response->assertValid()
            ->assertRedirect(route('home'));
    }

    public function testRegister()
    {
        $this->get(action([AuthController::class, 'register']))
            ->assertOk()
            ->assertSee('Зарегистрироваться')
            ->assertViewIs('auth.register');
    }

    public function testRegisterPost(): void
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
            action([AuthController::class, 'registerPost']),
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

    public function testLogout()
    {
        $password = '12345678';
        $user     = User::factory()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
        $response = $this->delete(action([AuthController::class, 'logout']));
        $this->assertGuest();
    }

    public function testForgotPassword()
    {
        $this->get(action([AuthController::class, 'forgotPassword']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    const ROUTE_PASSWORD_EMAIL        = 'password.email';
    const ROUTE_PASSWORD_REQUEST      = 'password.request';
    const ROUTE_PASSWORD_RESET        = 'password.reset';
    const ROUTE_PASSWORD_RESET_SUBMIT = 'password.reset.submit';
    const USER_ORIGINAL_PASSWORD      = 'secret';


    public function testForgotPasswordPost()
    {
        $this->get(action([AuthController::class, 'forgotPassword']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }
}