<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum OrderState: int implements DcatEnum
{
    use DcatEnumTrait;

    case OPENED = 1;
    case CLOSED = 2;
}
