<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\DcatEnumColored;
use Dcat\Admin\Traits\DcatEnumTrait;

enum ApiServerStatus: int implements DcatEnumColored
{

    use DcatEnumTrait;

    case DOWN = 0;
    case UP = 1;

    public function color() :string {
        return match($this) {
            self::DOWN => 'red',
            self::UP => 'green'
        };
    }
}
