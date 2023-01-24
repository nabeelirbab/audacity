<?php

namespace App\Admin\Controllers;

use App\Admin\Views\AccountInfoView;
use App\Admin\Views\DailyWinLossChartView;
use App\Admin\Views\GrowthView;
use App\Admin\Views\HourlyWinLossChartView;
use App\Admin\Views\InstrumentsChartView;
use App\Admin\Views\MonthlyGainChartView;
use App\Admin\Views\OpenTradesView;
use App\Admin\Views\TradeHistoryView;
use App\Admin\Views\TradingStatsShortView;
use App\Admin\Views\TradingStatsView;
use App\Models\Account;
use App\Models\AccountStat;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Routing\Controller;

class AccountAnalysisController extends Controller
{

    public function external(string $accountNumber, Content $content)
    {

        $from = request('from');
        $to = request('to');

        $content->full();

        return $this->view($accountNumber, $from, $to, $content);
    }

    public function internal(string $accountNumber, Content $content)
    {
        $from = request('from');
        $to = request('to');

        return $this->view($accountNumber, $from, $to, $content);
    }

    private function view(string $accountNumber, $from, $to, Content $content)
    {

        $account = Account
            ::where('account_number', $accountNumber)
            ->with(['user'])->first();

        if (!$account) {
            $content->description(___('not_found'));

            return $content;
        }

        if(!is_null($from) || !is_null($to)) {
            $stat = AccountStat::calcFiltered($accountNumber, $from, $to);
        } else {
            $stat = AccountStat::find($accountNumber);
        }

        $content->title($account->title);
        $content->description($account->user->name);

        $content->body(function (Row $row) use ($accountNumber, $stat, $from, $to) {

            $row->column(4, new TradingStatsShortView($stat, $from, $to));

            $monthly = new MonthlyGainChartView($accountNumber, $from, $to);
//            $daily = new DailyWinLossChartView($account->account_number);
//            $hourly = new HourlyWinLossChartView($account->account_number);
            $growth = new GrowthView($accountNumber, $from, $to);
            $instruments = new InstrumentsChartView($accountNumber, $from, $to);

            $tab = new Tab();

            $tab->add(___('growth'), $growth);
            $tab->add(___('monthly'), $monthly);
//            $tab->add(___('daily'), $daily);
//            $tab->add(___('hourly'), $hourly);
            $tab->add(___('instruments'), $instruments);

            $row->column(8, $tab);
        });

        $content->body(function (Row $row) use ($stat) {

            $tradingStat = new TradingStatsView($stat);
            $accountInfo = new AccountInfoView($stat);

            $tab = new Tab();

            $tab->add(___('trading_stats'), $tradingStat);
            $tab->add(___('account_info'), $accountInfo);

            $row->column(12, $tab);
        });

        $content->body(function (Row $row) use ($accountNumber, $from, $to) {

            $openTrades = new OpenTradesView($accountNumber);
            $tradeHistory = new TradeHistoryView($accountNumber, $from, $to);

            $tab = new Tab();
            $tab->add(___('open_trades'), $openTrades);
            $tab->add(___('trade_history'), $tradeHistory);

            $row->column(12, $tab);
        });

        return $content;
    }
}