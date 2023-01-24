<?php

namespace App\Enums;

use Dcat\Admin\DcatEnum;
use Dcat\Admin\Traits\DcatEnumTrait;

enum AccessTokenAbilityType: string implements DcatEnum
{
    use DcatEnumTrait;

    case MANAGE_USERS = 'manage-users';
    case MANAGE_SIGNALS = 'manage-signals';
    case MANAGE_ACCOUNTS = 'manage-accounts';
    case MANAGE_SUBSCRIPTIONS = 'manage-subscriptions';
    case MANAGE_PERFORMANCES = 'manage-performances';
}
