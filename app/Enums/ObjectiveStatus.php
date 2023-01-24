<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum ObjectiveStatus: int implements DcatEnum
{

    use DcatEnumTrait;

    case NONE = -1;
    case FAILED = 0;
    case PASSED = 1;
    case UNKNOWN = 2;

    public function color(): string
    {
        return match ($this) {
            self::FAILED                => 'red',
            self::PASSED                => 'green',
            default                     => ''
        };
    }
}