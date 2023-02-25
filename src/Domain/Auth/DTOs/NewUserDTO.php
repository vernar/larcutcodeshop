<?php

namespace Domain\Auth\DTOs;


use App\Http\Requests\RegisterPostRequest;
use Support\Traits\Makeable;

class NewUserDTO
{
    use Makeable;

    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    public static function fromRequest(RegisterPostRequest $request): NewUserDTO
    {
        return static::make(...$request->only(['name', 'email', 'password']));
    }
}