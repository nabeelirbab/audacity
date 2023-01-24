<?php

namespace App\Helpers;

class FmtHelper
{
    public static function prependDoll(float|int|string|null $value) : string
    {
        if(is_null($value))
            return '';

        if(is_string($value)) {
            $value = (float)$value;
        }

        if($value < 0) {
            return '-$'.abs($value);
        }

        return '$'.$value;
    }
}
