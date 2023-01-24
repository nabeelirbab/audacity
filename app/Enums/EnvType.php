<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum EnvType: int implements DcatEnum
{

    use DcatEnumTrait;

    case COPIER = 1;
    case PERFORMANCES = 2;
}
