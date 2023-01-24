<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum ChallengeStatus: int implements DcatEnum
{
    use DcatEnumTrait;

    case NEW = 0;
    case CANCELLED = 1;
    case CONFIRMED = 2;
    case ACTIVE = 3;
    case ENDED = 4;
    case ERROR = 5;
}
