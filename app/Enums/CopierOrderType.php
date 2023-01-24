<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum CopierOrderType: int implements DcatEnum
{
    use DcatEnumTrait;

    case BOTH = 0;
    case LONG_ONLY = 1;
    case SHORT_ONLY = 2;
}
