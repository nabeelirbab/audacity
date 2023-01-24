<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum YesNoType: int implements DcatEnum
{
    use DcatEnumTrait;

    case NO = 0;
    case YES = 1;
}
