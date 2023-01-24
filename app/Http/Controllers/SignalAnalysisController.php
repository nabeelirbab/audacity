<?php

namespace App\Http\Controllers;

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
use App\Models\CopierSignal;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Routing\Controller;

class SignalAnalysisController extends Controller
{

    public function index(string $slug, Content $content)
    {
        $content->full();
        $signal = CopierSignal::with(['senders','page_settings'])->public()->whereSlug($slug)->first();

        if(!$signal) {
            $content->title(__('mt4-account.not_found'));
            $content->body(__('mt4-account.not_found'));
            return $content;
        }

        $account = $signal->senders()->with(['stat', 'liveorders', 'closedorders'])->first();

        if(is_null($account)) {
            $content->title(__('mt4-account.not_found'));
            $content->body(__('mt4-account.not_found'));
            return $content;
        }

        $content->title($signal->title);

        $pageSettings = $signal->page_settings;

        $content->body(function ($row) use ($account, $pageSettings) {

            $row->column(4, new TradingStatsShortView($account->stat, $pageSettings));

            $monthly = new MonthlyGainChartView($account->account_number);
            //$daily = new DailyWinLossChartView($account->account_number);
            //$hourly = new HourlyWinLossChartView($account->account_number);
            $growth = new GrowthView($account->account_number);
            $instruments = new InstrumentsChartView($account->account_number);

            $tab = new Tab();

            $tab->add(__('mt4-account.growth'), $growth);
            $tab->add(__('mt4-account.monthly'), $monthly);
            //$tab->add(__('mt4-account.daily'), $daily);
            //$tab->add(__('mt4-account.hourly'), $hourly);
            $tab->add(__('mt4-account.instruments'), $instruments);

            $row->column(8, $tab);
        });

        $content->body(function ($row) use ($account, $pageSettings) {

            $tradingStat = new TradingStatsView($account->stat);
            $accountInfo = new AccountInfoView($account->stat, $pageSettings);

            $tab = new Tab();

            $tab->add(__('mt4-account.trading_stats'), $tradingStat);
            $tab->add(__('mt4-account.account_info'), $accountInfo);

            $row->column(12, $tab);
        });

        $content->body(function ($row) use ($account, $pageSettings) {

            $tab = new Tab();

            if( $pageSettings->hide_open_trades != 1 ) {
                $openTrades = new OpenTradesView($account->account_number);

                $tab->add(__('mt4-account.open_trades'), $openTrades);
            } else {
                $tab->add(__('mt4-account.open_trades'), '<i class="fa fa-lock"></i>');
            }

            if( $pageSettings->hide_trade_history != 1 ) {
                $tradeHistory = new TradeHistoryView($account->account_number);

                $tab->add(__('mt4-account.trade_history'), $tradeHistory);
            } else {
                $tab->add(__('mt4-account.trade_history'), '<i class="fa fa-lock"></i>');
            }

            $row->column(12, $tab);
        });

        return $content;
    }
}
