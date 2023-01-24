<?php

namespace App\Admin\Views;

use App\Enums\AccountStatus;
use App\Models\Account;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\InfoBox;

class AccountsOverviewInfoBox extends InfoBox
{

    public function render()
    {

        $online = Account::whereManagerId(Admin::user()->id)->whereIn('account_status', [AccountStatus::ONLINE, AccountStatus::CNN_LOST])->count();
        $invalid = Account::whereManagerId(Admin::user()->id)->whereIn('account_status', [AccountStatus::INVALID, AccountStatus::INVALID_STOPPED])->count();
        $orfant = Account::whereManagerId(Admin::user()->id)->whereIn('account_status', [AccountStatus::ORFANT])->count();
        $total = Account::whereManagerId(Admin::user()->id)->count();

        $this->withContent($online, $invalid, $orfant, $total);

        return parent::render();
    }

    protected function withContent($online, $invalid, $orfant, $total)
    {
        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-danger"></i> Orfant
    </div>
    <div>{$orfant}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-danger"></i> Invalid
    </div>
    <div>{$invalid}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-success"></i> Online
    </div>
    <div>{$online}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle primarywhite"></i> Total
    </div>
    <div>{$total}</div>
</div>
HTML
        );
    }
}