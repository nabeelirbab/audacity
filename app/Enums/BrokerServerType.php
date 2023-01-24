<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum BrokerServerType: int implements DcatEnum
{

    use DcatEnumTrait;

    case API = 0;
    case MANAGER = 1;
}
