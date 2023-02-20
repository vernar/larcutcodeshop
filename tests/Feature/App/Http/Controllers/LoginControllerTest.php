<?php

namespace Tests\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\LoginPostRequest;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogout()
    {
        $password = '12345678';
        $user     = UserFactory::new()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
        $response = $this->delete(action([LoginController::class, 'logout']));
        $this->assertGuest();
    }

    public function testPage()
    {
        $this->get(action([LoginController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function testHandle()
    {
        $password = '12345678';
        $user     = UserFactory::new()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $request = LoginPostRequest::factory()->create([
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([LoginController::class, 'handle']), $request);
        $response->assertValid()
            ->assertRedirect(route('home'));
    }

    public function testIncorrectLogin()
    {
        $password = 'correct-12345678';
        $user     = UserFactory::new()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $password = 'incorrect-12345678';
        $request  = LoginPostRequest::factory()->create([
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([LoginController::class, 'handle']), $request);
        $response->assertInvalid()->assertSessionHasErrors(["email" => "Пользователь не найден"]);
        $response->assertStatus(302);
    }
}
