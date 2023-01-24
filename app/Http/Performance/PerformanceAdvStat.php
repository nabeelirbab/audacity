<?php

namespace App\Http\Performance;

use App\Models\AccountStat;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Table;
use Illuminate\Support\Str;

class PerformanceAdvStat extends Box
{

    public function __construct(AccountStat $stat) {

        $fmt = function($val) {
            if(Str::length($val) == 0 || is_null($val))
                return '-';

            return $val;
        };

        $fmtDol = function($val) {

            if(Str::length($val) == 0 || is_null($val))
                return '-';

            if($val < 0)
                return '-$'.abs($val);


            return '$'.$val;
        };

        $data[] = ['Trades', $fmt($stat->nof_closed)];
        $data[] = ['Abs, Gain', $stat->abs_gain.'%'];
        $data[] = ['Daily', $stat->daily_perc.'%'];
        $data[] = ['Monthly', $stat->monthly_perc.'%'];
        $data[] = ['Profit(pips/$)', $fmt($stat->profit_pips).' / '.$fmtDol($stat->profit)];
        $data[] = ['Win/Loss', $fmt($stat->nof_won).' / '.$fmt($stat->nof_lost)];

        $data1[] = ['Average Win (pips/$)', $fmt($stat->avg_win_pips).' / '.$fmtDol($stat->avg_win_dol)];
        $data1[] = ['Average Loss (pips/$)', $fmt($stat->avg_loss_pips).' / '.$fmtDol($stat->avg_loss_dol)];
        $data1[] = ['Longs Won', $fmt($stat->longs_won)];
        $data1[] = ['Shorts Won', $fmt($stat->shorts_won)];
        $data1[] = ['Best Trade(pips/$)', $fmt($stat->best_trade_pips).' / '.$fmtDol($stat->best_trade_dol)];
        $data1[] = ['Worst Trade(pips/$)', $fmt($stat->worst_trade_pips).' / '.$fmtDol($stat->worst_trade_dol)];

        $table = new Table([], $data);
        $table1 = new Table([], $data1);

        $r = new Row();

        $r->column(6, $table->render());
        $r->column(6, $table1->render());

        parent::__construct( trans('trading-objectives.status'), $r->render() );
    }
}