<?php

namespace Tests\Feature\Auth\DTOs;

use App\Http\Requests\RegisterPostRequest;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewUserDTOTest extends \Tests\TestCase
{
    use RefreshDatabase;

    public function testInstanceCreatedFromRequest()
    {
        $dto = NewUserDTO::fromRequest(new RegisterPostRequest([
            'name'     => 'test',
            'email'    => 'testing@gmail.com',
            'password' => '12345',
        ]));

        $this->assertInstanceOf(NewUserDTO::class, $dto);
    }
}