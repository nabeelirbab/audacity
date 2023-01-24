<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\ResetTargetBatchAction;
use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceObjectiveType;
use App\Enums\PerformanceStatus;
use App\Models\Performance;
use App\Models\PerformancePlan;
use App\Models\PerformanceWithObjectives;
use App\Models\User;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Database\Eloquent\Collection;

class TradingObjectiveController extends AdminController
{

    protected $translation = 'trading-objectives';

    protected function grid() {
        return new Grid(PerformanceWithObjectives::with(['target', 'stat', 'user', 'account:id,account_status,title,account_number', 'plan']), function (Grid $grid) {

            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->column('user.name', ___('user'))->link(function() {
                return admin_url('clients') .'/' . $this->user_id;
            },'');

            $grid->column('account.account_number', ___('account_number'))->link(function() {
                if(is_null($this->account)) {
                    return ___('account_removed');
                }
                return admin_url('accounts') .'/' . $this->account->id;
            },'');
            $grid->column('plan.title', 'plan');

            $grid->combine('max_daily_loss', ['stat.max_daily_loss', 'target.max_daily_loss', 'plan.max_daily_loss_perc'], __('trading-objectives.max_daily_loss'));
            $grid->column('stat.max_daily_loss', __('trading-objectives.res'))->price();
            $grid->column('target.max_daily_loss', __('trading-objectives.tar'))->price();
            $grid->column('plan.max_daily_loss_perc', __('trading-objectives.percent'))->percent();

            $grid->combine('max_loss', ['stat.max_loss', 'target.max_loss', 'plan.max_loss_perc'], __('trading-objectives.max_loss'));
            $grid->column('stat.max_loss', __('trading-objectives.res'))->price();
            $grid->column('target.max_loss', __('trading-objectives.tar'))->price();
            $grid->column('plan.max_loss_perc', __('trading-objectives.percent'))->percent();

            $grid->combine('profit', ['stat.profit', 'target.profit', 'plan.profit_perc'], __('trading-objectives.profit'));
            $grid->column('stat.profit', __('trading-objectives.res'))->price();
            $grid->column('target.profit', __('trading-objectives.tar'))->price();
            $grid->column('plan.profit_perc', __('trading-objectives.percent'))->percent();

            $grid->combine('min_days', ['stat.days_traded', 'target_min_trading_days'], __('trading-objectives.trading_days'));
            $grid->column('stat.days_traded', __('trading-objectives.res'));
            $grid->column('target_min_trading_days', __('trading-objectives.target_trading_days'))
                ->display(function($val) {
                    return $this->target->min_trading_days.'/'.$this->target->max_trading_days;
                });

            $grid->column('stat.hedging_detected', __('trading-objectives.hedging'))->bool()
                ->help(__('trading-objectives.help_hedging_prohobited'));
            $grid->column('stat.sl_not_used', __('trading-objectives.sl_not_used'))->bool()
                ->help('trading-objectives.help_sl_not_used');

            $grid->column('started_at',__('trading-objectives.started'))
                ->display(function($date) {
                    if(is_null($date))
                        return ___('never_traded');

                    return $date;
                });
            $grid->column('ended_at',__('trading-objectives.ended'))
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

                    /** @var \App\Repositories\PerformanceObjectivesRepository $r */
                    $o = $row->objectives;

                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::PROFIT), 'profit');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::MAX_DAILY_LOSS), 'max_daily_loss');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::MAX_LOSS), 'max_loss');
                    $fmt($row, $o->getObjectiveStatus(PerformanceObjectiveType::TRADING_DAYS), 'days_traded');

                    $link = '';
                    if($row->status != PerformanceStatus::CALCULATING->label()) {
                        $v = __('trading-objectives.view');
                        $link = "<a href='/trading-objectives/view/{$row->slug}'>{$v}</a>";
                    }

                    $row->column('slug', $link);
                });
            });

            $grid->filter(function ( $filter) {
                $users  = User::whereManagerId(User::GetManagerId())->pluck('username', 'id');
                $users->prepend(Admin::user()->name, Admin::user()->id);
                $filter->equal('user_id', ___('user'))->select($users);

                $plans  = PerformancePlan
                    ::enabled()
                    ->whereManagerId(User::GetManagerId())->pluck('title', 'id');
                $filter->equal('performance_plan_id', ___('plan'))->select($plans);

                $filter->disableIdFilter();
            });

            $grid->tools(function ($tools) {

                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->divider();
                    $batch->add(new ResetTargetBatchAction(___('reset')));
                });
            });

            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableBatchDelete();

            $grid->showColumnSelector();
            $grid->hideColumns(['stat.sl_not_used','stat.hedging_detected']);

            $grid->export()->driver()->rows(function(Collection $data) {
                $export = [];

                foreach ($data as $row) {
                    /** @var Performance $row */
                    $export[] =  [
                        'user.name' => $row->user->name,
                        'user.email' => $row->user->email,
                        'account_number' => $row->account_number,
                        'plan.title' => $row->plan->title,
                        'max_daily_loss.result'=> $row->stat->max_daily_loss,
                        'max_daily_loss.target'=> $row->target->max_daily_loss,
                        'max_daily_loss.plan'=> $row->plan->max_daily_loss_perc,
                        'max_loss.result'=> $row->stat->max_loss,
                        'max_loss.target'=> $row->target->max_loss,
                        'max_loss.plan'=> $row->plan->max_loss_perc,
                        'profit.result'=> $row->stat->profit,
                        'profit.target'=> $row->target->profit,
                        'profit.plan'=> $row->plan->profit_perc,
                        'min_trading_days.result'=> $row->stat->days_traded,
                        'min_trading_days.target'=> $row->target->min_trading_days,
                        'min_trading_days.plan'=> $row->plan->min_trading_days,
                        'max_trading_days.target'=> $row->target->max_trading_days,
                        'max_trading_days.plan'=> $row->plan->max_trading_days,
                        'started_at'=> $row->started_at,
                        'ended_at' => $row->ended_at
                    ];
                }
                return collect($export);
            })->titles([
                'user.name' => ___('user'),
                'user.email' => ___('email'),
                'account_number' => ___('account'),
                'plan.title' => ___('plan'),
                'max_daily_loss.result' => ___('max_daily_loss_result'),
                'max_daily_loss.target' => ___('max_daily_loss_target'),
                'max_daily_loss.plan' => ___('max_daily_loss_plan'),
                'max_loss.result' => ___('max_loss_result'),
                'max_loss.target' => ___('max_loss_target'),
                'max_loss.plan' => ___('max_loss_plan'),
                'profit.result' => ___('profit_result'),
                'profit.target' => ___('profit_target'),
                'profit.plan' => ___('profit_plan'),
                'min_trading_days.result' => ___('min_trading_days_result'),
                'min_trading_days.target' => ___('min_trading_days_target'),
                'min_trading_days.plan' => ___('min_trading_days_plan'),
                'max_trading_days.target' => ___('max_trading_days_target'),
                'max_trading_days.plan' => ___('max_trading_days_plan'),
                'started_at' => ___('started_at'),
                'ended_at' => ___('ended_at')
            ])->xlsx();
        });
    }
}
