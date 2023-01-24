<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum PerformanceStatus: int implements DcatEnum
{
    use DcatEnumTrait;

    case CALCULATING = 0;
    case ACTIVE = 1;
    case ENDED_PASSED = 2;
    case ENDED_FAILED = 3;
}
