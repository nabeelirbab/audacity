<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum CopierType: int implements DcatEnum
{

    use DcatEnumTrait;

    case SENDER   = 1;
    case FOLLOWER    = 2;
    case STRATEGY = 3;

}
