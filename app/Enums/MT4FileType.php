<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum MT4FileType: string implements DcatEnum
{

    use DcatEnumTrait;

    case SRV = 'SRV';
    case TPL = 'Tpl';
    case LIB = 'Lib';
    case FILE = 'File';
    case EX4 = 'Ex4';
    case EXE = 'Exe';
    case PAIRS = 'Pairs';
}
