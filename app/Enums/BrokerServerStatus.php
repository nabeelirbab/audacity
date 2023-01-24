<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum BrokerServerStatus: int implements DcatEnum
{
    use DcatEnumTrait;

    case NONE = 0;
    case ACTIVE = 1;
}
