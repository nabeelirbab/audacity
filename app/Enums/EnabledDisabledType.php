<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum EnabledDisabledType: int implements DcatEnum
{

    use DcatEnumTrait;

    case DISABLED = 0;
    case ENABLED = 1;
}
