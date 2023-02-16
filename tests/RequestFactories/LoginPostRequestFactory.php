<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class LoginPostRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'email'    => $this->faker->email,
            'password' => bcrypt($this->faker->password(8)),
        ];
    }
}
