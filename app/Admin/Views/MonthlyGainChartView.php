<?php

namespace App\Admin\Views;

use App\Models\Account;
use Carbon\Carbon;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Illuminate\Contracts\Support\Renderable;

class MonthlyGainChartView implements Renderable
{

    private $accountNumber;
    private $from;
    private $to;

    public function __construct($accountNumber, $from = null, $to = null) {
        $this->accountNumber = $accountNumber;
        $this->from = $from;
        $this->to = $to;
    }

    public function render() {
        return $this->build();
    }

    private function build()
    {

        $data = Account::monthlyGainChartData($this->accountNumber, $this->from, $this->to);
        $month = $data->keys()->map(function($key) {
            return Carbon::parse($key)->format('M y');
        });

        $chart = new Chart([
            'chart' => [
                'type'     => 'bar',
                'height' => 252,
                'toolbar' => [
                    'show' => false
                ]
            ],
            'plotOptions' => [
                'bar' => [
                  'colors' => [
                    'ranges' => [
                        [
                            'from' => -100,
                            'to' => 0,
                            'color' => '#F15B46'
                        ]
                    ]
                  ]
                ]
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'xaxis' => [
                'type' => 'text',
                'categories' => $month
            ],
            'series' =>[
                [
                    'name' => '%',
                    'data' => $data->values()->toArray()
                ]
            ]
        ]);

        return $chart->render();
    }

}
