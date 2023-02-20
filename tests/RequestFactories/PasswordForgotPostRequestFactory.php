<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class PasswordForgotPostRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->email,
        ];
    }
}
