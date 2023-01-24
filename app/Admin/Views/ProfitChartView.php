<?php

namespace App\Admin\Views;

use App\Models\Account;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ProfitChartView implements Renderable
{

    private $accountNumber;
    private $height;

    public function __construct(int $accountNumber, $height) {
        $this->height = $height;
        $this->accountNumber = $accountNumber;
    }

    public function render() {
        $data = Account::growthChartData($this->accountNumber);

        $chart = new Chart([
            'chart' => [
                'type' => 'area',
                'toolbar' => [
                    'show' => false,
                ],
                'height' => $this->height,
                'sparkline' => [
                    'enabled' => true,
                ],
                'grid' => [
                    'show' => false,
                    'padding' => [
                        'left' => 0,
                        'right' => 0,
                    ],
                ],
            ],
            'tooltip' => [
                'x' => [
                    'show' => false,
                ],
            ],
            'xaxis' => [
                'labels' => [
                    'show' => false,
                ],
                'axisBorder' => [
                    'show' => false,
                ],
            ],
            'yaxis' => [
                'y' => 0,
                'offsetX' => 0,
                'offsetY' => 0,
                'padding' => ['left' => 0, 'right' => 0],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'width' => 2.5,
                'curve' => 'smooth',
            ],
            'fill' => [
                'opacity' => 0.1,
                'type' => 'solid',
            ],
            'series' => [
                [
                    'name' => __('mt4-account.growth').' %',
                    'data' => $data,
                ],
            ],

        ]);

        return $chart->render();
    }


}