<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum CopierDirection: int implements DcatEnum
{

    use DcatEnumTrait;

    case NORMAL = 0;
    case INVERSE = 1;
}
