<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum CopierRiskType: int implements DcatEnum
{

    use DcatEnumTrait;

    case MULTIPLIER = 1;
    case FIXED_LOT = 2;
    case MONEY_RATIO = 3;
    case RISK_PERCENT = 4;
    case SCALING = 5;

    public function shortTitle() : string
    {
        return match($this) {
          self::FIXED_LOT => 'Fixed',
          self::MULTIPLIER => 'Mult',
          self::MONEY_RATIO => 'Ratio',
          self::RISK_PERCENT => 'Perc',
          self::SCALING => 'Scaling',
          default => $this->value
        };
    }
}
