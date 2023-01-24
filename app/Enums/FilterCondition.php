<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum FilterCondition: int implements DcatEnum
{

    use DcatEnumTrait;

    case NONE = 0;
    case EQUAL = 1;
    case NOT_EQUAL = 2;
    case CONTAIN = 3;
    case NOT_CONTAIN = 4;
}
