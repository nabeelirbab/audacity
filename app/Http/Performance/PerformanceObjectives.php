<?php

namespace App\Http\Performance;

use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceObjectiveType;
use App\Helpers\FmtHelper;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;
use App\Repositories\PerformanceObjectivesRepository;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Table;

class PerformanceObjectives extends Box
{

    public function __construct(PerformanceTarget $target, PerformanceStat $stat, PerformanceObjectivesRepository $objectives) {

        $fmt = function ( ObjectiveStatus $status) {
            $color = $status->color();
            $title = $status->label();

            return "<span class='badge' style='background:$color'>$title</span>";
        };

        $trading_days = $target->min_trading_days.'/'.$target->max_trading_days;
        if($target->min_trading_days == 0 && $target->max_trading_days == 0) {
            $trading_days = 'Unlim';
        }

        if($target->min_trading_days != 0 && $target->max_trading_days == 0) {
            $trading_days = 'Min '.$target->min_trading_days;
        }

        if($target->min_trading_days == 0 && $target->max_trading_days != 0) {
            $trading_days = 'Max '.$target->max_trading_days;
        }

        $table = new Table(
            [
                trans('trading-objectives.trading_objective'),
                trans('trading-objectives.result'),
                trans('trading-objectives.status')
            ],
            [
                [
                    '<b>'.trans('trading-objectives.max_daily_loss').' '.FmtHelper::prependDoll($target->max_daily_loss).'</b>',
                    FmtHelper::prependDoll($stat->max_daily_loss),
                    $fmt($objectives->getObjectiveStatus(PerformanceObjectiveType::MAX_DAILY_LOSS))
                ],
                [
                    '<b>'.trans('trading-objectives.max_loss').' '.FmtHelper::prependDoll($target->max_loss).'</b>',
                    FmtHelper::prependDoll($stat->max_loss),
                    $fmt($objectives->getObjectiveStatus(PerformanceObjectiveType::MAX_LOSS))
                ],
                [
                    '<b>'.trans('trading-objectives.profit_target').' '.FmtHelper::prependDoll($target->profit).'</b>',
                    FmtHelper::prependDoll($stat->profit),
                    $fmt($objectives->getObjectiveStatus(PerformanceObjectiveType::PROFIT))
                ],
                [
                    '<b>'.trans('trading-objectives.trading_days').' '.$trading_days.'</b>',
                    $stat->days_traded,
                    $fmt($objectives->getObjectiveStatus(PerformanceObjectiveType::TRADING_DAYS))
                ]
            ]
        );

        parent::__construct( trans('trading-objectives.objectives'), $table->render() );
    }
}