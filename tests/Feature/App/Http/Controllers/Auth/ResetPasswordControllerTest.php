<?php

namespace Tests\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\LoginPostRequest;
use App\Http\Requests\PasswordForgotPostRequest;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    const USER_EMAIL    = 'testuser@gmail.com';
    const USER_PASSWORD = '12345678';

    public function test_make_reset_password()
    {
        $user  = $this->_getTestUser();
        $token = Password::createToken($user);

        $newPassword = 'new-12345678';
        $this->post(action(
                [ResetPasswordController::class, 'handle'],
                [
                    'email'                 => $user->email,
                    'password'              => $newPassword,
                    'password_confirmation' => $newPassword,
                    'token'                 => $token,
                ])
        );

        $this->assertGuest();
        $requestLogin = LoginPostRequest::factory()->create([
            'email'    => $user->email,
            'password' => $newPassword,
        ]);
        $response     = $this->post(action([LoginController::class, 'handle']), $requestLogin);
        $response->assertValid()
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function testPage()
    {
        $user    = $this->_getTestUser();
        $request = PasswordForgotPostRequest::factory()->create([
            'email' => $user->email,
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), $request)
            ->assertRedirect();

        $user = $this->_getTestUser();
        $this->get(
            action(
                [ResetPasswordController::class, 'page'],
                [
                    'token' => $user->remember_token,
                    'email' => $user->email,
                ])
        )
            ->assertOk()
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');
    }

    protected function _getTestUser(): User
    {
        $user = User::query()
            ->where('email', self::USER_EMAIL)
            ->first();
        if (!$user) {
            $user = UserFactory::new()->create([
                'email'    => self::USER_EMAIL,
                'password' => bcrypt(self::USER_PASSWORD),
            ]);
        }

        return $user;
    }
}
