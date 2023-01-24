<?php

namespace App\Enums;

final class MT4RunResult
{
    private int $code;
    private ?string $message;

    public function __construct( int $code, string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function getMessage() : ?string
    {
        return $this->message;
    }
}
