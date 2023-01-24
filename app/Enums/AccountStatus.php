<?php

namespace App\Enums;

use Dcat\Admin\DcatEnumColored;
use Dcat\Admin\Traits\DcatEnumTrait;

enum AccountStatus: int implements DcatEnumColored
{

    use DcatEnumTrait;

    case ORFANT = -1;
    case NONE = 0;
    case ONLINE = 1;
    case OFFLINE = 2;
    case PENDING = 3;
    case SUSPEND = 4;
    case REMOVING = 5;
    case INVALID = 6;
    case SUSPEND_STOPPED = 7;
    case INVALID_STOPPED = 8;
    case VERIFYING = 9;
    case CNN_LOST = 12;

    public function color(): string
    {
        return match ($this) {
            self::ONLINE, self::CNN_LOST                => '#49d758',
            self::NONE,
            self::OFFLINE, self::PENDING,
            self::INVALID, self::INVALID_STOPPED        => '#db633e',
            default => '#db633e'
        };
    }
}
