<?php

namespace App\Enums;

use Dcat\Admin\Admin;
use Dcat\Admin\DcatEnumColored;
use Dcat\Admin\Traits\DcatEnumTrait;

enum HelpdeskTicketStatus: int implements DcatEnumColored
{

    use DcatEnumTrait;

    case OPEN = 1;
    case CLOSED = 2;

    public function color() :string {
        return match($this) {
            self::OPEN => Admin::color()->primary(),
            self::CLOSED => Admin::color()->warning(),
            default => Admin::color()->warning()
        };
    }
}
