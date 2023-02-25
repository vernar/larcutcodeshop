<?php

namespace Tests\Feature\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterNewUserActionTest extends \Tests\TestCase
{
    use RefreshDatabase;

    public function testSuccessUserCreate()
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'test@gmail.com',
        ]);
        $action = app(RegisterNewUserContract::class);
        $action(NewUserDTO::make('Test', 'test@gmail.com', '123456789'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
        ]);
    }
}