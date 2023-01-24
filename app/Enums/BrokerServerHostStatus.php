<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum BrokerServerHostStatus: int implements DcatEnum
{

    use DcatEnumTrait;

    case NONE = 0;
    case ONLINE = 1;
    case OFFLINE = 2;
}
