<?php

namespace App\Admin\Views;

use App\Models\AccountStat;
use Dcat\Admin\Widgets\Table;

class TradingStatsView extends Table
{

    public function __construct(AccountStat $stat, $style=[]) {

        $data[] = [
            __('mt4-account.total_trades'),
            $stat->nof_processed,
            __('mt4-account.longs_won'),
            $stat->longs_won.' of '.$stat->total_longs
        ];
        $data[] = [
            __('mt4-account.win_perc'),
            number_format($stat->win_ratio, 2).'% '.$stat->nof_won.' of '.$stat->nof_processed,
            __('mt4-account.shorts_won'),
            $stat->shorts_won.' of '. $stat->total_shorts
        ];

        $data[] = [
            __('mt4-account.loss_perc'),
            number_format($stat->loss_ratio, 2).'% '.$stat->nof_lost.' of '.$stat->nof_processed,
            __('mt4-account.best_trade'),
            $stat->best_trade_dol.' '.$stat->currency
        ];

        $data[] = [
            __('mt4-account.lots'),
            $stat->total_lots,
            __('mt4-account.worst_trade'),
            $stat->worst_trade_dol.' '.$stat->currency
        ];

        $data[] = [
            __('mt4-account.commission'),
            $stat->total_commission.' '.$stat->currency,
            __('mt4-account.average_win'),
            number_format($stat->avg_win, 2 ).' '.$stat->currency
        ];

        $data[] = [
            __('mt4-account.swap'),
            $stat->total_swap.' '.$stat->currency,
            __('mt4-account.average_loss'),
            number_format( $stat->avg_loss, 2 ).' '.$stat->currency
        ];

        $this->withBorder();
        parent::__construct([], $data, $style);
    }


}
