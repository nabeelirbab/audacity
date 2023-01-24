<?php

namespace App\Admin\Controllers;

use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceObjectiveType;
use App\Enums\PerformanceStatus;
use App\Models\PerformanceWithObjectives;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class MyTradingObjectiveController extends AdminController
{

    protected $translation = 'trading-objectives';

    protected function grid() {
        return new Grid(PerformanceWithObjectives::with(['target', 'stat', 'plan', 'account:id,account_status,title,account_number']), function (Grid $grid) {

            $grid->model()->whereUserId(Admin::user()->id);

            $grid->column('account.account_number', ___('account_number'))->link(function() {
                return admin_route('my-accounts.index') .'/' . $this->account->id;
            },'');
            $grid->column('plan.title','Plan');

            $grid->combine('max_daily_loss', ['stat.max_daily_loss', 'target.max_daily_loss', 'plan.max_daily_loss_perc'], trans('trading-objectives.max_daily_loss'));
            $grid->column('stat.max_daily_loss', trans('trading-objectives.res'))->price();
            $grid->column('target.max_daily_loss', trans('trading-objectives.tar'))->price();
            $grid->column('plan.max_daily_loss_perc', trans('trading-objectives.percent'))->percent();

            $grid->combine('max_loss', ['stat.max_loss', 'target.max_loss', 'plan.max_loss_perc'], trans('trading-objectives.max_loss'));
            $grid->column('stat.max_loss', trans('trading-objectives.res'))->price();
            $grid->column('target.max_loss', trans('trading-objectives.tar'))->price();
            $grid->column('plan.max_loss_perc', trans('trading-objectives.percent'))->percent();

            $grid->combine('profit', ['stat.profit', 'target.profit', 'plan.profit_perc'], trans('trading-objectives.profit'));
            $grid->column('stat.profit', trans('trading-objectives.res'))->price();
            $grid->column('target.profit', trans('trading-objectives.tar'))->price();
            $grid->column('plan.profit_perc', trans('trading-objectives.percent'))->percent();

            $grid->combine('min_days', ['stat.days_traded', 'target_min_trading_days'], trans('trading-objectives.trading_days'));
            $grid->column('stat.days_traded', trans('trading-objectives.res'));
            $grid->column('target_min_trading_days', trans('trading-objectives.target_trading_days'))
                ->display(function($val) {
                    return $this->target->min_trading_days.'/'.$this->target->max_trading_days;
                });

            // $grid->column('stat.deposit', trans('trading-objectives.deposit'))
            //     ->display(function($val){ if(empty($val)) return ''; return '$'.$val;});

            $grid->column('started_at',trans('trading-objectives.started'))
                ->display(function($date) {
                    if(is_null($date))
                        return ___('never_traded');

                    return $date;
                });
            $grid->column('ended_at',trans('trading-objectives.ended'))
                ->display(function($date) {
                    if(!is_null($date))
                        return Carbon::parse($date)->format('Y-m-d');
                    return '-';
                });

            $grid->column('status')->enum();
            $grid->slug(___('results'));

            $grid->rows(function($rows) {

                $rows->map(function (Grid\Row $row) {

                    $fmt = function (Grid\Row $row, ObjectiveStatus $status, string $key) {
                        $color = $status->color();
                        $val = $row->stat[$key];

                        $row->column('stat.'.$key, "<span class='badge' style='background:$color'>$val</span>");
                    };

                    /** @var \App\Repositories\PerformanceObjectivesRepository $o */
                    $o = $row->objectives;

                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::PROFIT), 'profit');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::MAX_DAILY_LOSS), 'max_daily_loss');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::MAX_LOSS), 'max_loss');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::TRADING_DAYS), 'days_traded');

                    $link = '';
                    if($row->status != PerformanceStatus::CALCULATING->label()) {
                        $v = trans('trading-objectives.view');
                        $link = "<a href='/trading-objectives/view/{$row->slug}'>{$v}</a>";
                    }

                    $row->column('slug', $link);
                });
            });

            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableRowSelector();
        });
    }
}