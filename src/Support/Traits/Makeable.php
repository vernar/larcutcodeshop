<?php

namespace Support\Traits;

trait Makeable
{
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }
}