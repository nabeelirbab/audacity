<?php

namespace App\Admin\Views;

use App\Models\Account;
use App\Models\AccountStat;
use App\Models\CopierSignalFollower;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form\Field\Button;
use Dcat\Admin\Form\Field\Date;
use Dcat\Admin\Widgets\Modal;
use Dcat\Admin\Widgets\Table;

class TradingStatsShortView extends Table
{

    public function __construct(AccountStat $stat, $from = null, $to = null,  $settings = null, $style=[]) {

        $fmt = function($val, $currency) use($settings) {

            $val = number_format($val, 2).' '.$currency;

            if(!$settings)
                return $val;

            return $settings->hide_balance_info ? '<i class="fa fa-lock"></i>' : $val;
        };

        $account = Account::with('asFollower:title')->whereAccountNumber($stat->account_number)->first(['id']);

        $data[] = [
            __('mt4-account.filter'),
            $this->_buildFilter($stat, $from, $to)
        ];

        $data[] = [
            __('mt4-account.first_trade_day'),
            Carbon::parse($stat->first_trade_day)->format('m/d/Y')
        ];

        $data[] = [
            __('mt4-account.last_trade_day'),
            Carbon::parse($stat->last_trade_day)->format('m/d/Y')
        ];

        $data[] = [
            __('mt4-account.days_trading'),
            $stat->total_days.' ('. number_format($stat->daily_perc, 2).'% per day)'
        ];

        if(count($account->asFollower) > 0) {
            $data[] = [
                __('mt4-account.signals'),
                $account->asFollower->implode('title', ',')
            ];
        }

        // if(count($account->asFollower) > 0) {
        //     $follower = CopierSignalFollower::where('account_id', $account->id)->first();
        //     $data[] = [
        //         '<a href="http://tradeautomation.com/supercharge" target="_blank">'.__('mt4-account.supercharge_status').'</a>',
        //         $follower->scaling_factor.'x'
        //     ];
        // }

        $data[] = [
            __('mt4-account.current_pace'),
            number_format( $stat->current_pace, 2).'% per year'
        ];

        $data[] = [
            __('mt4-account.growth'),
            number_format( $stat->gain_perc, 2).'%'
        ];

        $data[] = [
            __('mt4-account.profit_loss'),
            $fmt($stat->total_profit, $stat->currency)
        ];

        $data[] = [
            __('mt4-account.avg_profit_per_day'),
            $fmt($stat->avg_profit_per_day, $stat->currency )
        ];

        $data[] = [
            __('mt4-account.balance'),
            $fmt($stat->balance, $stat->currency)
        ];

        $data[] = [
            __('mt4-account.equity'),
            $fmt($stat->equity, $stat->currency)
        ];

        $data[] = [
            __('mt4-account.avg_trades_per_day'),
            number_format( $stat->avg_trades_per_day, 2 )
        ];

        $this->withBorder();
        parent::__construct([], $data, $style);
    }

    private function _buildFilter(AccountStat $stat, $from, $to) {
        $hasFilter = false;

        if(!is_null($from) || !is_null($to)) {
            $hasFilter = true;
        }

        // if(is_null($from) && !is_null($stat->first_trade_day)) {
        //     $from = $stat->first_trade_day;
        // } else {
        //     $from = Carbon::now()->format('Y-m-d');
        // }

        if(is_null($from)) {
            $from = '2022-04-18'; // ::todo::remove on tamanager quit
        }

        if(is_null($to) && !is_null($stat->last_trade_day)) {
            $to = $stat->last_trade_day;
        } else {
            $to = Carbon::now()->format('Y-m-d');;
        }

        $dateFrom = (new Date('from'))->value($from);
        $dateTo = (new Date('to'))->value($to);

        $clickApply =  <<<JS
            var url = window.location.href;
            var from = $("input[name='from']").val();
            var to = $("input[name='to']").val();

            if(url.indexOf('?') != -1) {
                url = url.split('?')[0];
            }
            url = url + '?from=' + from + '&to=' + to;

            window.location.replace(url);
        JS;

        $clickReset =  <<<JS
            var url = window.location.href;

            if(url.indexOf('?') != -1) {
                window.location.replace(url.split('?')[0]);
            }

        JS;

        $btnApply = new Button(__('mt4-account.apply_filter'));
        $btnApply->on('click', $clickApply);

        $btnReset = new Button(__('mt4-account.reset_filter'));
        $btnReset->on('click', $clickReset);

        Admin::js(Date::$js);
        Admin::css(Date::$css);

        $body =
            <<<HTML
            <div class="fields-group">
                {$dateFrom}
                {$dateTo}
                <div class="pull-right" style="display: inline-flex;">
                {$btnApply}
                {$btnReset}
            </div>

            </div>
            HTML;

        $filter = Modal::make();
        $style = '';
        if($hasFilter)
            $style = 'btn-primary';
        $filter->button('<button class="btn '.$style.'"><i class="feather icon-filter"></i></button>');
        $filter->title(__('mt4-account.custom_analysis'));
        $filter->body($body);

        return $filter;
    }

}
