<?php

namespace App\Enums;

final class MT4RunResultType
{

    const OK = 0;
    const FAILED_REPEATABLE = 1;
    const FAILED = 2;
    const ACCOUNT_INVALID = 4;
    const FAILED_W_ALERT = 8;
}
