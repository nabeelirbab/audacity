<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum AccountType: int implements DcatEnum
{

    use DcatEnumTrait;

    case DEMO = 0;
    case REAL = 1;
}
