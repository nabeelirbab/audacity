<?php

namespace App\Enums;

use Dcat\Admin\DcatEnumColored;
use Dcat\Admin\Traits\DcatEnumTrait;

enum SystemLogLevel: int implements DcatEnumColored
{

    use DcatEnumTrait;

    case EMERGENCY = 600;
//    case ALERT     = 550;
    case CRITICAL  = 500;
    case ERROR     = 400;
    case WARNING   = 300;
//    case NOTICE    = 250;
    case INFO      = 200;
//    case DEBUG     = 100;

    public function color(): string {
        return match ($this) {
            self::INFO => '#1976D2',
            self::ERROR => '#FF5722',
            self::CRITICAL=> '#F44336',
            self::WARNING => '#FF9100',
            self::EMERGENCY => '#B71C1C',
            default => '#8A8A8A'
        };

    }
}
