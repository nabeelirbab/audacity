<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum MT4Timeframe: int implements DcatEnum
{

    use DcatEnumTrait;

    case M1 = 1;
    case M5 = 5;
    case M15 = 15;
    case M30 = 30;
    case H1 = 60;
    case H4 = 240;
}
