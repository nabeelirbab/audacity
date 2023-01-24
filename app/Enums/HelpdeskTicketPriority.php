<?php

namespace App\Enums;

use Dcat\Admin\Admin;
use Dcat\Admin\DcatEnumColored;
use Dcat\Admin\Traits\DcatEnumTrait;

enum HelpdeskTicketPriority : int implements DcatEnumColored
{

    use DcatEnumTrait;

    case NORMAL = 1;
    case HIGH = 2;

    public function color() :string {
        return match($this) {
            self::NORMAL => Admin::color()->primary(),
            self::HIGH => Admin::color()->danger(),
            default => Admin::color()->danger()
        };
    }
}
