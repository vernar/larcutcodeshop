<?php

namespace Tests\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Requests\PasswordForgotPostRequest;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle()
    {
        $password = '12345678';
        $user     = UserFactory::new()->create([
            'email'    => 'testuser@gmail.com',
            'password' => bcrypt($password),
        ]);

        $request = PasswordForgotPostRequest::factory()->create([
            'email' => $user->email,
        ]);

        $response = $this
            ->post(action([ForgotPasswordController::class, 'handle']), $request);
        $response->assertRedirect();
    }

    public function testPage()
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }
}
