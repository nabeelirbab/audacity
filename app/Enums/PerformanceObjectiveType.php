<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum PerformanceObjectiveType: int implements DcatEnum
{

    use DcatEnumTrait;

    case MAX_LOSS = 1;
    case MAX_DAILY_LOSS = 2;
    case PROFIT = 3;
    case TRADING_DAYS = 4;
    case HEDGING_DETECTED = 5;
    case SL_NOT_USED = 6;
}
