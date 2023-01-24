<?php

namespace App\Admin\Grids;

use App\Admin\Extensions\Tools\AddSignalFollowerButton;
use App\Admin\Extensions\Tools\ResetPerformanceButton;
use App\Admin\Extensions\Tools\ResetTargetBatchAction;
use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceObjectiveType;
use App\Enums\PerformanceStatus;
use Carbon\Carbon;
use Dcat\Admin\Grid;

class TradingObjectiveGrid extends Grid {

    /**
     * Create a new grid instance.
     *
     * Grid constructor.
     *
     * @param  Repository|\Illuminate\Database\Eloquent\Model|Builder|null  $repository
     * @param  null|\Closure  $builder
     */
    public function __construct($repository = null, ?\Closure $builder = null, $request = null)
    {
        parent::__construct($repository, $builder, $request);

        //$this->disableBatchActions();
        $this->disableCreateButton();
        //$this->disableRowSelector();
        $this->disableActions();

        $this->column('account_number', __('trading-objectives.account_number'));
        $this->column('plan.title', __('trading-objectives.plan'));

        $this->combine('max_daily_loss', ['stat.max_daily_loss', 'target.max_daily_loss', 'plan.max_daily_loss_perc'], __('trading-objectives.max_daily_loss'));
        $this->column('stat.max_daily_loss', __('trading-objectives.res'))->price();
        $this->column('target.max_daily_loss', __('trading-objectives.tar'))->price();
        $this->column('plan.max_daily_loss_perc', __('trading-objectives.percent'))->percent();

        $this->combine('max_loss', ['stat.max_loss', 'target.max_loss', 'plan.max_loss_perc'], __('trading-objectives.max_loss'));
        $this->column('stat.max_loss', __('trading-objectives.res'))->price();
        $this->column('target.max_loss', __('trading-objectives.tar'))->price();
        $this->column('plan.max_loss_perc', __('trading-objectives.percent'))->percent();

        $this->combine('profit', ['stat.profit', 'target.profit', 'plan.profit_perc'], __('trading-objectives.profit'));
        $this->column('stat.profit', __('trading-objectives.res'))->price();
        $this->column('target.profit', __('trading-objectives.tar'))->price();
        $this->column('plan.profit_perc', __('trading-objectives.percent'))->percent();

        $this->combine('min_days', ['stat.days_traded', 'target_min_trading_days'], __('trading-objectives.trading_days'));
        $this->column('stat.days_traded', __('trading-objectives.res'));
        $this->column('target_min_trading_days', __('trading-objectives.target_trading_days'))
            ->display(function($val) {
                return $this->target->min_trading_days.'/'.$this->target->max_trading_days;
            });

        // $this->column('stat.deposit', __('trading-objectives.deposit'))
        //     ->display(function($val){ if(empty($val)) return ''; return '$'.$val;});

        $this->column('started_at',__('trading-objectives.started'))
            ->display(function($date) {
                if(is_null($date))
                    return __('trading-objectives.never_traded');

                return $date;
            });
        $this->column('ended_at',__('trading-objectives.ended'))
            ->display(function($date) {
                if(!is_null($date))
                    return Carbon::parse($date)->format('Y-m-d');
                return '-';
            });

        $this->column('status')->enum();
        $this->slug(__('trading-objectives.results'));

        $this->tools(function ($tools) {

            $tools->batch(function (Grid\Tools\BatchActions $batch) {
                $batch->divider();
                $batch->add(new ResetTargetBatchAction(___('reset')));
            });
        });

        $this->rows(function($rows) {

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
                    $v = __('trading-objectives.view');
                    $link = "<a href='/trading-objectives/view/{$row->slug}'>{$v}</a>";
                }

                $row->column('slug', $link);
            });
        });
    }
}