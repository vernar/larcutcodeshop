<?php

namespace Support\Flash;

class FlashMessage
{
    public function __construct(protected string $message, protected string $class)
    {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}