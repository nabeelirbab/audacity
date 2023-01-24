<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum TemplateLoadStatusType: int implements DcatEnum
{
    use DcatEnumTrait;

    case EMPTY    = 0;
    case LOADED   = 1;
    case FAILED   = 2;
    case WRONG_EA = 3;
}
