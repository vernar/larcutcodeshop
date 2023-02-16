<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class RegisterPostRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'email'    => $this->faker->email,
            'name'     => $this->faker->name,
            'password' => bcrypt($this->faker->password(8)),
        ];
    }
}
