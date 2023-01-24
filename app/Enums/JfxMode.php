<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum JfxMode: int implements DcatEnum
{
    use DcatEnumTrait;

    case SYNC_DISABLED = 0;
    case DEBUG_ENABLED = 1;
    case CALC_STAT = 2;
    case MYSQL_SKIP_OWN = 4;
    case MYSQL_WATCHER = 8;
    case CLOSE_WHEN = 16;
    case LOAD_EAS = 32;
    case WEBHOOK_ENABLED = 64;
    case RUN_EXES = 128;
    case COPIER_DISABLED = 256;
    case HAS_ADV_FILTER = 512;
}