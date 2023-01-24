<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum OrderStatus: int implements DcatEnum
{
    use DcatEnumTrait;

    case NOT_FILLED = 0;
    case OPEN = 1;
//    case UPDATED = 2;
    case CLOSED = 3;
}
