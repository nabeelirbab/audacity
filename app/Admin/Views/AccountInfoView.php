<?php

namespace App\Admin\Views;

use App\Enums\AccountType;
use App\Models\AccountStat;
use Dcat\Admin\Widgets\Table;

class AccountInfoView extends Table
{

    public function __construct(AccountStat $stat, $settings = null, $style=[]) {

        $fmtBalance = function($val) use($settings) {
            if(!$settings)
                return $val;
            return $settings->hide_balance_info ? '<i class="fa fa-lock"></i>' : $val;
        };

        $fmt = function($val) use($settings) {
            if(!$settings)
                return $val;
            return $settings->hide_broker_info ? '<i class="fa fa-lock"></i>' : $val;
        };

        $data[] = [
            __('mt4-account.balance'),
            $fmtBalance($stat->balance.' '.$stat->currency),
            __('mt4-account.broker'),
            $fmt($stat->broker_company)
        ];
        $data[] = [
            __('mt4-account.deposits'),
            $fmtBalance($stat->deposit.' '.$stat->currency),
            __('mt4-account.broker_server'),
            $fmt($stat->broker_server)
        ];

        $data[] = [
            __('mt4-account.withdrawals'),
            $fmtBalance($stat->withdrawal.' '.$stat->currency),
            __('mt4-account.leverage'),
            $fmt($stat->leverage.':1')
        ];

        $this->withBorder();
        parent::__construct([], $data, $style);
    }


}
