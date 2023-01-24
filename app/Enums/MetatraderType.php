<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum MetatraderType: int implements DcatEnum
{
    use DcatEnumTrait;

    case V4 = 4;
    case V5 = 5;
}
