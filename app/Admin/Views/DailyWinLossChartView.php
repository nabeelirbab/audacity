<?php

namespace App\Admin\Views;

use App\Enums\WeekDayType;
use App\Models\Account;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Illuminate\Contracts\Support\Renderable;

class DailyWinLossChartView implements Renderable
{

    private $accountNumber;
    public function __construct($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    public function render() {
        return $this->build($this->accountNumber);
    }

    private function build($accountNumber)
    {

        $data = Account::dailyWinLossChartData($accountNumber);

        $dataWinners = $data->pluck('winners')->toArray();
        $dataLosers = $data->pluck('losers')->toArray();
        $days = $data->keys()->map(function(int $day) {
            return WeekDayType::from($day)->label();
        });

        $chart = new Chart();
            $chart->chart([
                'type'     => 'bar',
                'stacked' => true,
                'height' => 252,
                'toolbar' => [
                    'show' => false
                ],
            ])
            ->dataLabels([
                'enabled' => false
            ])
            ->xaxis([
                'type' => 'text',
                'categories' => $days
            ])
            ->series([
                [
                    'name' => trans('mt4-account.winners'),
                    'data' => $dataWinners
                ],
                [
                    'name' => trans('mt4-account.losers'),
                    'data' => $dataLosers
                ],
            ]);

        return $chart->render();
    }

}
