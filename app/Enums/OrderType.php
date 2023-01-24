<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum OrderType: int implements DcatEnum
{
    use DcatEnumTrait;

    case BUY = 0;
    case SELL = 1;
    case BUY_LIMIT =2;
    case SELL_LIMIT = 3;
    case BUY_STOP = 4;
    case SELL_STOP = 5;
    case BALANCE = 6;
    case CREDIT = 7;

    public static function marketTypes()
    {
        return [self::BUY, self::SELL];
    }

    public static function countableTypes()
    {
        return [self::BUY, self::SELL, self::BALANCE];
    }

    public function color(): string
    {
        return match ($this) {
            self::BUY                => 'green',
            self::SELL               => 'red',
            default                     => ''
        };
    }
}
